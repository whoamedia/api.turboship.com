<?php

namespace App\Integrations\Shopify;


use App\Integrations\Shopify\Api\CarrierServiceApi;
use App\Integrations\Shopify\Api\CollectApi;
use App\Integrations\Shopify\Api\OrderApi;
use App\Integrations\Shopify\Api\ProductApi;
use App\Integrations\Shopify\Api\WebHookApi;

class ShopifyIntegration
{

    /**
     * @var ShopifyConfiguration
     */
    protected $shopifyConfiguration;

    /**
     * @var CarrierServiceApi
     */
    public $carrierServiceApi;

    /**
     * @var CollectApi
     */
    public $collectApi;

    /**
     * @var OrderApi
     */
    public $orderApi;

    /**
     * @var ProductApi
     */
    public $productApi;

    /**
     * @var WebHookApi
     */
    public $webHookApi;

    /**
     * ShopifyIntegration constructor.
     * @param ShopifyConfiguration $shopifyConfiguration
     */
    public function __construct(ShopifyConfiguration $shopifyConfiguration)
    {
        $this->shopifyConfiguration     = $shopifyConfiguration;

        $this->carrierServiceApi        = new CarrierServiceApi($this->shopifyConfiguration);
        $this->collectApi               = new CollectApi($this->shopifyConfiguration);
        $this->productApi               = new ProductApi($this->shopifyConfiguration);
        $this->orderApi                 = new OrderApi($this->shopifyConfiguration);
        $this->webHookApi               = new WebHookApi($this->shopifyConfiguration);
    }



}