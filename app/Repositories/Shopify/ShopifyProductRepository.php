<?php

namespace App\Repositories\Shopify;


use App\Integrations\Shopify\Exceptions\ShopifyItemNotFoundException;
use App\Integrations\Shopify\Models\Requests\GetShopifyProductCount;
use App\Integrations\Shopify\Models\Requests\GetShopifyProducts;

class ShopifyProductRepository extends BaseShopifyRepository
{

    /**
     * @param   int             $page
     * @param   int             $limit
     * @param   string|null     $ids
     * @return  \App\Integrations\Shopify\Models\Responses\ShopifyProduct[]
     */
    public function getImportCandidates ($page = 1, $limit = 250, $ids = null)
    {
        $getShopifyProducts             = new GetShopifyProducts();

        if (!is_null($ids))
            $getShopifyProducts->setIds($ids);

        $getShopifyProducts->setPage($page);
        $getShopifyProducts->setLimit($limit);
        $getShopifyProducts->setPublishedStatus('published');
        $getShopifyProducts->setOrder('created_at desc');


        $shopifyProductsResponse        = $this->shopifyIntegration->productApi->get($getShopifyProducts);

        return $shopifyProductsResponse;
    }

    /**
     * @param   string      $ids
     * @return  int
     */
    public function getImportCandidatesCount ($ids = null)
    {
        $getShopifyProducts             = new GetShopifyProductCount();
        $getShopifyProducts->setIds($ids);
        $getShopifyProducts->setPublishedStatus('published');

        $shopifyProductsResponse        = $this->shopifyIntegration->productApi->count($getShopifyProducts);
        return $shopifyProductsResponse;
    }

    /**
     * @param   int     $id
     * @return  \App\Integrations\Shopify\Models\Responses\ShopifyProduct|null
     */
    public function show ($id)
    {
        try
        {
            return $this->shopifyIntegration->productApi->show($id);
        }
        catch (ShopifyItemNotFoundException $shopifyItemNotFoundException)
        {
            return null;
        }
    }
}
