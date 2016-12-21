<?php

namespace App\Http\Controllers\WebHooks;


use App\Http\Controllers\Controller;
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

    /**
     * @var string
     */
    protected $shopifySharedSecret;

    public function __construct (Request $request)
    {
        $this->clientIntegrationRepo    = EntityManager::getRepository('App\Models\Integrations\ClientIntegration');
        $this->integrationWebHookRepo   = EntityManager::getRepository('App\Models\Integrations\IntegrationWebHook');
        $this->webHookLogRepo           = EntityManager::getRepository('App\Models\Logs\WebHookLog');

        $clientIntegrationId            = $request->route('id');
        $this->clientIntegration        = $this->clientIntegrationRepo->getOneById($clientIntegrationId);

        $credentialUtility              = new CredentialUtility($this->clientIntegration);
        $this->shopifySharedSecret      = $credentialUtility->getShopifySharedSecret()->getValue();


        $topic                          = str_replace('webhooks/shopify/' . $request->route('id') . '/', '', $request->path());

        $webHookQuery   = [
            'topics'                    => $topic,
            'integrationIds'            => $this->clientIntegration->getIntegration()->getId(),
        ];

        $webHookResult                  = $this->integrationWebHookRepo->where($webHookQuery);

        $integrationWebHook             = $webHookResult[0];

        $this->webHookLog                     = new WebHookLog();
        $verified                       = $this->verifyWebHook($request);
        $this->webHookLog->setVerified($verified);
        $this->webHookLog->setClientIntegration($this->clientIntegration);
        $this->webHookLog->setIntegrationWebHook($integrationWebHook);
        $this->webHookLog->setIncomingMessage(json_encode($request->input(), true));
        $this->webHookLog->setSuccess(true);

        $this->webHookLogRepo->saveAndCommit($this->webHookLog);
    }

    private function verifyWebHook (Request $request)
    {
        $data                           = file_get_contents('php://input');

        $hmac_header                    = $request->header('X-Shopify-Hmac-Sha256');
        $calculated_hmac                = base64_encode(hash_hmac('sha256', $data, $this->shopifySharedSecret, true));

        return ($hmac_header == $calculated_hmac);
    }

}