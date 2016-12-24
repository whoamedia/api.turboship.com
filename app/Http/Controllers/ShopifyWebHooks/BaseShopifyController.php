<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Http\Controllers\Controller;
use App\Models\CMS\Client;
use App\Models\Integrations\ClientECommerceIntegration;
use App\Models\Logs\ShopifyWebHookLog;
use App\Repositories\Doctrine\Integrations\ClientECommerceIntegrationRepository;
use App\Repositories\Doctrine\Logs\ShopifyWebHookLogRepository;
use App\Services\CredentialService;
use Illuminate\Http\Request;
use EntityManager;

class BaseShopifyController extends Controller
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientECommerceIntegrationRepository
     */
    protected $clientECommerceIntegrationRepo;

    /**
     * @var ShopifyWebHookLogRepository
     */
    protected $shopifyWebHookLogRepo;

    /**
     * @var ClientECommerceIntegration
     */
    protected $clientECommerceIntegration;

    /**
     * @var ShopifyWebHookLog
     */
    protected $shopifyWebHookLog;


    public function __construct (Request $request)
    {
        $this->clientECommerceIntegrationRepo = EntityManager::getRepository('App\Models\Integrations\ClientECommerceIntegration');
        $this->shopifyWebHookLogRepo    = EntityManager::getRepository('App\Models\Logs\ShopifyWebHookLog');

        $clientECommerceIntegrationId   = $request->route('id');
        $this->clientECommerceIntegration= $this->clientECommerceIntegrationRepo->getOneById($clientECommerceIntegrationId);
        $this->client                   = $this->clientECommerceIntegration->getClient();

        $credentialService              = new CredentialService($this->clientECommerceIntegration);
        $shopifySharedSecret            = $credentialService->getShopifySharedSecret()->getValue();

        $topic                          = str_replace('webhooks/shopify/' . $request->route('id') . '/', '', $request->path());

        $this->shopifyWebHookLog        = new ShopifyWebHookLog();
        $this->shopifyWebHookLog->setTopic($topic);
        $verified                       = $this->verifyWebHook($request, $shopifySharedSecret);
        $this->shopifyWebHookLog->setVerified($verified);
        $this->shopifyWebHookLog->setClientECommerceIntegration($this->clientECommerceIntegration);
        $this->shopifyWebHookLog->setIncomingMessage(json_encode($request->input(), true));
        $this->shopifyWebHookLog->setSuccess(true);

        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
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