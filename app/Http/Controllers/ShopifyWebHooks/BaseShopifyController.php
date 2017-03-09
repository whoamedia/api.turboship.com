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
     * @var string
     */
    protected $json;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var IntegratedShoppingCartRepository
     */
    protected $integratedShoppingCartRepo;

    /**
     * @var ShopifyWebHookLogRepository
     */
    protected $shopifyWebHookLogRepo;

    /**
     * @var IntegratedShoppingCart
     */
    protected $integratedShoppingCart;

    /**
     * @var ShopifyWebHookLog
     */
    protected $shopifyWebHookLog;


    public function __construct ()
    {
        $this->integratedShoppingCartRepo = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
        $this->shopifyWebHookLogRepo    = EntityManager::getRepository('App\Models\Logs\ShopifyWebHookLog');
    }

    public function handleRequest (Request $request)
    {
        $this->json                     = json_encode($request->input(), true);
        $integratedShoppingCartId       = $request->route('id');
        $this->integratedShoppingCart   = $this->integratedShoppingCartRepo->getOneById($integratedShoppingCartId);
        $this->client                   = $this->integratedShoppingCart->getClient();

        $credentialService              = new CredentialService($this->integratedShoppingCart);
        $shopifySharedSecret            = $credentialService->getShopifySharedSecret()->getValue();

        $topic                          = str_replace('webhooks/shopify/' . $request->route('id') . '/', '', $request->path());

        $this->shopifyWebHookLog        = new ShopifyWebHookLog();
        $this->shopifyWebHookLog->setTopic($topic);
        $verified                       = $this->verifyWebHook($request, $shopifySharedSecret);
        $this->shopifyWebHookLog->setVerified($verified);
        $this->shopifyWebHookLog->setIntegratedShoppingCart($this->integratedShoppingCart);
        $this->shopifyWebHookLog->setIncomingMessage($this->json);
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