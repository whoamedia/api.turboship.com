<?php

namespace App\Repositories\Shopify;


use App\Integrations\Shopify\Models\Requests\GetShopifyProductCount;
use App\Integrations\Shopify\Models\Requests\GetShopifyProducts;

class ShopifyProductRepository extends BaseShopifyRepository
{

    /**
     * @param   int $page
     * @param   int $limit
     * @return  \App\Integrations\Shopify\Models\Responses\ShopifyProduct[]
     */
    public function getImportCandidates ($page = 1, $limit = 250)
    {
        $getShopifyProducts             = new GetShopifyProducts();

        $getShopifyProducts->setPage($page);
        $getShopifyProducts->setLimit($limit);
        $getShopifyProducts->setPublishedStatus('published');

        $shopifyProductsResponse        = $this->shopifyIntegration->productApi->get($getShopifyProducts);
        return $shopifyProductsResponse;
    }

    /**
     * @return  int
     */
    public function getImportCandidatesCount ()
    {
        $getShopifyProducts             = new GetShopifyProductCount();
        $getShopifyProducts->setPublishedStatus('published');
        $shopifyProductsResponse        = $this->shopifyIntegration->productApi->count($getShopifyProducts);
        return $shopifyProductsResponse;
    }

}