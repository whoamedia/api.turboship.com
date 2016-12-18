<?php

namespace App\Services\Shopify;


use App\Services\Shopify\Api\CollectApi;
use App\Services\Shopify\Api\OrderApi;
use App\Services\Shopify\Api\ProductApi;

class ShopifyService
{

    /**
     * @var ShopifyConfiguration
     */
    protected $shopifyConfiguration;

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
     * ShopifyService constructor.
     * @param ShopifyConfiguration $shopifyConfiguration
     */
    public function __construct(ShopifyConfiguration $shopifyConfiguration)
    {
        $this->shopifyConfiguration     = $shopifyConfiguration;

        $this->collectApi               = new CollectApi($this->shopifyConfiguration);
        $this->productApi               = new ProductApi($this->shopifyConfiguration);
        $this->orderApi                 = new OrderApi($this->shopifyConfiguration);
    }



}