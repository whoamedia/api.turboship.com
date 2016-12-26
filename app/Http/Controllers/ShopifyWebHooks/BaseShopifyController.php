<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Http\Controllers\Controller;
use App\Models\CMS\Client;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Logs\ShopifyWebHookLog;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
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
     * @var IntegratedShoppingCartRepository
     */
    protected $integratedShoppingCartCartRepo;

    /**
     * @var ShopifyWebHookLogRepository
     */
    protected $shopifyWebHookLogRepo;

    /**
     * @var IntegratedShoppingCart
     */
    protected $integratedShoppingCartCart;

    /**
     * @var ShopifyWebHookLog
     */
    protected $shopifyWebHookLog;


    public function __construct ()
    {
        $this->integratedShoppingCartCartRepo = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
        $this->shopifyWebHookLogRepo    = EntityManager::getRepository('App\Models\Logs\ShopifyWebHookLog');
    }

    public function handleRequest (Request $request)
    {
        $integratedShoppingCartCartId   = $request->route('id');
        $this->integratedShoppingCartCart= $this->integratedShoppingCartCartRepo->getOneById($integratedShoppingCartCartId);
        $this->client                   = $this->integratedShoppingCartCart->getClient();

        $credentialService              = new CredentialService($this->integratedShoppingCartCart);
        $shopifySharedSecret            = $credentialService->getShopifySharedSecret()->getValue();

        $topic                          = str_replace('webhooks/shopify/' . $request->route('id') . '/', '', $request->path());

        $this->shopifyWebHookLog        = new ShopifyWebHookLog();
        $this->shopifyWebHookLog->setTopic($topic);
        $verified                       = $this->verifyWebHook($request, $shopifySharedSecret);
        $this->shopifyWebHookLog->setVerified($verified);
        $this->shopifyWebHookLog->setIntegratedShoppingCart($this->integratedShoppingCartCart);
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
    protected function verifyWebHook (Request $request, $shopifySharedSecret)
    {
        $data                           = file_get_contents('php://input');

        $hmac_header                    = $request->header('X-Shopify-Hmac-Sha256');
        $calculated_hmac                = base64_encode(hash_hmac('sha256', $data, $shopifySharedSecret, true));

        return ($hmac_header == $calculated_hmac);
    }

}