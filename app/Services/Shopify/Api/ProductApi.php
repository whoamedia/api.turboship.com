<?php

namespace App\Services\Shopify\Api;


use App\Services\Shopify\Models\Requests\GetShopifyProductCount;
use App\Services\Shopify\Models\Requests\GetShopifyProducts;
use App\Services\Shopify\Models\Responses\Product;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ProductApi extends BaseApi
{

    /**
     * @param   GetShopifyProducts|array        $request
     * @return  Product[]
     */
    public function get ($request = [])
    {
        $request                        = $request instanceof GetShopifyProducts ? $request : new GetShopifyProducts($request);
        $response                       = parent::makeHttpRequest('get', '/products.json', $request);
        $items                          = AU::get($response['products'], []);

        $result                         = [];
        foreach ($items AS $product)
        {
            $result[]                   = new Product($product);
        }

        return $result;
    }

    /**
     * @param   GetShopifyProductCount|array    $request
     * @return  int
     */
    public function count ($request = [])
    {
        $request                        = $request instanceof GetShopifyProductCount ? $request : new GetShopifyProductCount($request);
        $response                       = parent::makeHttpRequest('get', '/products/count.json', $request);
        return $response['count'];
    }
}