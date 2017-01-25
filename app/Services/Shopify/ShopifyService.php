<?php

namespace App\Services\Shopify;


use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Integrations\IntegratedWebHook;
use jamesvweston\Shopify\Models\Requests\CreateShopifyWebHook;
use jamesvweston\Shopify\Models\Requests\GetShopifyOrderCount;
use jamesvweston\Shopify\Models\Requests\GetShopifyOrders;
use jamesvweston\Shopify\Models\Requests\GetShopifyProductCount;
use jamesvweston\Shopify\Models\Requests\GetShopifyProducts;
use jamesvweston\Shopify\ShopifyConfiguration;
use jamesvweston\Shopify\ShopifyClient;
use App\Services\CredentialService;

class ShopifyService
{

    /**
     * @var ShopifyClient
     */
    public $shopifyClient;

    /**
     * @var IntegratedShoppingCart
     */
    public $integratedShoppingCart;


    /**
     * ShopifyService constructor.
     * @param IntegratedShoppingCart        $integratedShoppingCart
     */
    public function __construct(IntegratedShoppingCart $integratedShoppingCart)
    {
        $this->integratedShoppingCart   = $integratedShoppingCart;

        $credentialService              = new CredentialService($this->integratedShoppingCart);
        $apiKey                         = $credentialService->getShopifyApiKey()->getValue();
        $password                       = $credentialService->getShopifyPassword()->getValue();
        $hostName                       = $credentialService->getShopifyHostName()->getValue();
        $sharedSecret                   = $credentialService->getShopifySharedSecret()->getValue();

        $shopifyConfiguration           = new ShopifyConfiguration();
        $shopifyConfiguration->setApiKey($apiKey);
        $shopifyConfiguration->setPassword($password);
        $shopifyConfiguration->setHostName($hostName);
        $shopifyConfiguration->setSharedSecret($sharedSecret);

        $this->shopifyClient            = new ShopifyClient($shopifyConfiguration);
    }

    /**
     * @param   int             $page
     * @param   int             $limit
     * @param   string|null     $status
     * @return  \jamesvweston\Shopify\Models\Responses\ShopifyOrder[]
     */
    public function getOrderImportCandidates ($page = 1, $limit = 250, $status = null)
    {
        $getShopifyOrders               = new GetShopifyOrders();
        $getShopifyOrders->setFulfillmentStatus('unshipped');
        $getShopifyOrders->setFinancialStatus('paid');
        $getShopifyOrders->setTest(false);

        if (!is_null($status))
            $getShopifyOrders->setStatus($status);

        $getShopifyOrders->setOrder('created_at asc');
        $getShopifyOrders->setPage($page);
        $getShopifyOrders->setLimit($limit);

        $shopifyOrdersResponse          = $this->shopifyClient->orderApi->get($getShopifyOrders);
        return $shopifyOrdersResponse;
    }

    /**
     * @param   string|null     $status
     * @return  int
     */
    public function getOrderImportCandidatesCount ($status = null)
    {
        $getShopifyOrderCount           = new GetShopifyOrderCount();
        $getShopifyOrderCount->setFulfillmentStatus('unshipped');
        $getShopifyOrderCount->setFinancialStatus('paid');
        $getShopifyOrderCount->setTest(false);


        if (!is_null($status))
            $getShopifyOrderCount->setStatus($status);

        $shopifyOrderCountResponse      = $this->shopifyClient->orderApi->count($getShopifyOrderCount);
        return $shopifyOrderCountResponse;
    }

    /**
     * @return  int
     */
    public function getOrdersShippedCount ()
    {
        $getShopifyOrderCount           = new GetShopifyOrderCount();
        $getShopifyOrderCount->setFulfillmentStatus('shipped');
        $getShopifyOrderCount->setFinancialStatus('paid');
        $getShopifyOrderCount->setTest(false);
        $getShopifyOrderCount->setStatus('closed');

        $shopifyOrderCountResponse      = $this->shopifyClient->orderApi->count($getShopifyOrderCount);
        return $shopifyOrderCountResponse;
    }

    /**
     * @param   int             $page
     * @param   int             $limit
     * @return  \jamesvweston\Shopify\Models\Responses\ShopifyOrder[]
     */
    public function getOrdersShipped ($page = 1, $limit = 250)
    {
        $getShopifyOrders               = new GetShopifyOrders();
        $getShopifyOrders->setFulfillmentStatus('shipped');
        $getShopifyOrders->setFinancialStatus('paid');
        $getShopifyOrders->setTest(false);
        $getShopifyOrders->setStatus('closed');

        $getShopifyOrders->setOrder('created_at asc');
        $getShopifyOrders->setPage($page);
        $getShopifyOrders->setLimit($limit);

        $shopifyOrdersResponse          = $this->shopifyClient->orderApi->get($getShopifyOrders);
        return $shopifyOrdersResponse;
    }

    /**
     * @param   int             $page
     * @param   int             $limit
     * @param   string|null     $ids
     * @return  \jamesvweston\Shopify\Models\Responses\ShopifyProduct[]
     */
    public function getProductImportCandidates ($page = 1, $limit = 250, $ids = null)
    {
        $getShopifyProducts             = new GetShopifyProducts();

        if (!is_null($ids))
            $getShopifyProducts->setIds($ids);

        $getShopifyProducts->setPage($page);
        $getShopifyProducts->setLimit($limit);
        $getShopifyProducts->setPublishedStatus('published');
        $getShopifyProducts->setOrder('created_at desc');


        $shopifyProductsResponse        = $this->shopifyClient->productApi->get($getShopifyProducts);

        return $shopifyProductsResponse;
    }

    /**
     * @param   string      $ids
     * @return  int
     */
    public function getProductImportCandidatesCount ($ids = null)
    {
        $getShopifyProducts             = new GetShopifyProductCount();
        $getShopifyProducts->setIds($ids);
        $getShopifyProducts->setPublishedStatus('published');

        $shopifyProductsResponse        = $this->shopifyClient->productApi->count($getShopifyProducts);
        return $shopifyProductsResponse;
    }

    /**
     * @param   IntegratedWebHook $integratedWebHook
     * @return  IntegratedWebHook
     */
    public function createWebHook (IntegratedWebHook $integratedWebHook)
    {
        $createShopifyWebHook           = new CreateShopifyWebHook();
        $createShopifyWebHook->setFormat('json');

        $topic                          = $integratedWebHook->getIntegrationWebHook()->getTopic();
        $createShopifyWebHook->setTopic($topic);

        //  config('app.url')       'https://dev-api.turboship.com'
        $address    = config('app.url') . '/webhooks/shopify/' . $this->integratedShoppingCart->getId() . '/' . $topic;
        $createShopifyWebHook->setAddress($address);

        $response                       = $this->shopifyClient->webHookApi->create($createShopifyWebHook);

        $integratedWebHook->setExternalId($response->getId());
        $integratedWebHook->setUrl($response->getAddress());
        return $integratedWebHook;
    }


    public function deleteWebHook ($id)
    {
        return $this->shopifyClient->webHookApi->delete($id);
    }

}