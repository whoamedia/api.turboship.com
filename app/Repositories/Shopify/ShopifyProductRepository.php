<?php

namespace App\Repositories\Shopify;


use App\Integrations\Shopify\Models\Requests\GetShopifyProducts;

class ShopifyProductRepository extends BaseShopifyRepository
{

    /**
     * @return \App\Integrations\Shopify\Models\Responses\ShopifyProduct[]
     */
    public function getImportCandidates ()
    {
        $getShopifyProducts             = new GetShopifyProducts();

        $getShopifyProducts->setPage(1);
        $getShopifyProducts->setLimit(250);

        $shopifyProductsResponse        = $this->shopifyIntegration->productApi->get($getShopifyProducts);
        return $shopifyProductsResponse;
    }

}