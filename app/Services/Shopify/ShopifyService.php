<?php

namespace App\Services\Shopify;


use App\Services\Shopify\Api\ProductApi;

class ShopifyService
{

    /**
     * @var ShopifyConfiguration
     */
    protected $shopifyConfiguration;

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

        $this->productApi               = new ProductApi($this->shopifyConfiguration);
    }



}