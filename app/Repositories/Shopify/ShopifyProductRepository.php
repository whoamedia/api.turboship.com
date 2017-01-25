<?php

namespace App\Repositories\Shopify;


use jamesvweston\Shopify\Exceptions\ShopifyItemNotFoundException;
use jamesvweston\Shopify\Models\Requests\GetShopifyProductCount;
use jamesvweston\Shopify\Models\Requests\GetShopifyProducts;

class ShopifyProductRepository extends BaseShopifyRepository
{

    /**
     * @param   int             $page
     * @param   int             $limit
     * @param   string|null     $ids
     * @return  \jamesvweston\Shopify\Models\Responses\ShopifyProduct[]
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


        $shopifyProductsResponse        = $this->shopifyClient->productApi->get($getShopifyProducts);

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

        $shopifyProductsResponse        = $this->shopifyClient->productApi->count($getShopifyProducts);
        return $shopifyProductsResponse;
    }

    /**
     * @param   int     $id
     * @return  \jamesvweston\Shopify\Models\Responses\ShopifyProduct|null
     */
    public function show ($id)
    {
        try
        {
            return $this->shopifyClient->productApi->show($id);
        }
        catch (ShopifyItemNotFoundException $shopifyItemNotFoundException)
        {
            return null;
        }
    }
}
