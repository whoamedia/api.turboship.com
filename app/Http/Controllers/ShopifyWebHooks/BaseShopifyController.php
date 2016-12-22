<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Http\Controllers\Controller;
use App\Models\CMS\Client;
use App\Models\Integrations\ClientIntegration;
use App\Models\Logs\WebHookLog;
use App\Repositories\Doctrine\Integrations\ClientIntegrationRepository;
use App\Repositories\Doctrine\Integrations\IntegrationWebHookRepository;
use App\Repositories\Doctrine\Logs\WebHookLogRepository;
use App\Utilities\CredentialUtility;
use Illuminate\Http\Request;
use EntityManager;

class BaseShopifyController extends Controller
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientIntegrationRepository
     */
    protected $clientIntegrationRepo;

    /**
     * @var IntegrationWebHookRepository
     */
    protected $integrationWebHookRepo;

    /**
     * @var WebHookLogRepository
     */
    protected $webHookLogRepo;

    /**
     * @var ClientIntegration
     */
    protected $clientIntegration;

    /**
     * @var WebHookLog
     */
    protected $webHookLog;


    public function __construct (Request $request)
    {
        $this->clientIntegrationRepo    = EntityManager::getRepository('App\Models\Integrations\ClientIntegration');
        $this->integrationWebHookRepo   = EntityManager::getRepository('App\Models\Integrations\IntegrationWebHook');
        $this->webHookLogRepo           = EntityManager::getRepository('App\Models\Logs\WebHookLog');

        $clientIntegrationId            = $request->route('id');
        $this->clientIntegration        = $this->clientIntegrationRepo->getOneById($clientIntegrationId);
        $this->client                   = $this->clientIntegration->getClient();

        $credentialUtility              = new CredentialUtility($this->clientIntegration);
        $shopifySharedSecret            = $credentialUtility->getShopifySharedSecret()->getValue();


        $topic                          = str_replace('webhooks/shopify/' . $request->route('id') . '/', '', $request->path());

        $webHookQuery   = [
            'topics'                    => $topic,
            'integrationIds'            => $this->clientIntegration->getIntegration()->getId(),
        ];

        $webHookResult                  = $this->integrationWebHookRepo->where($webHookQuery);

        $integrationWebHook             = $webHookResult[0];

        $this->webHookLog                     = new WebHookLog();
        $verified                       = $this->verifyWebHook($request, $shopifySharedSecret);
        $this->webHookLog->setVerified($verified);
        $this->webHookLog->setClientIntegration($this->clientIntegration);
        $this->webHookLog->setIntegrationWebHook($integrationWebHook);
        $this->webHookLog->setIncomingMessage(json_encode($request->input(), true));
        $this->webHookLog->setSuccess(true);

        $this->webHookLogRepo->saveAndCommit($this->webHookLog);
    }

    /**
     * Validate that the request is coming from Shopify
     * @param   Request $request
     * @param   string  $shopifySharedSecret
     * @return  bool
     */
    private function verifyWebHook (Request $request, $shopifySharedSecret)
    {
        $data                           = file_get_contents('php://input');

        $hmac_header                    = $request->header('X-Shopify-Hmac-Sha256');
        $calculated_hmac                = base64_encode(hash_hmac('sha256', $data, $shopifySharedSecret, true));

        return ($hmac_header == $calculated_hmac);
    }

}