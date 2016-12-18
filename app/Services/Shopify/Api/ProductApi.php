<?php

namespace App\Services\Shopify\Api;


use App\Services\Shopify\Models\Requests\CreateShopifyProduct;
use App\Services\Shopify\Models\Requests\GetShopifyProductCount;
use App\Services\Shopify\Models\Requests\GetShopifyProducts;
use App\Services\Shopify\Models\Responses\Product;
use App\Services\Shopify\Models\Responses\Variant;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ProductApi extends BaseApi
{

    /**
     * @see     https://help.shopify.com/api/reference/product#index
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
     * @see     https://help.shopify.com/api/reference/product#show
     * @param   int         $id
     * @return  Product|null
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', '/products/' . $id . '.json');

        $items                          = AU::get($response['product']);
        return is_null($items) ? null : new Product($items);
    }

    /**
     * @see     https://help.shopify.com/api/reference/product#count
     * @param   GetShopifyProductCount|array    $request
     * @return  int
     */
    public function count ($request = [])
    {
        $request                        = $request instanceof GetShopifyProductCount ? $request : new GetShopifyProductCount($request);
        $response                       = parent::makeHttpRequest('get', '/products/count.json', $request);
        return $response['count'];
    }

    /**
     * @see     https://help.shopify.com/api/reference/product#create
     * @param   CreateShopifyProduct|array       $request
     * @return  Product
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateShopifyProduct ? $request : new CreateShopifyProduct($request);
        $response                       = parent::makeHttpRequest('post', '/products.json', ['product' => $request->jsonSerialize()]);
        return new Product($response['product']);
    }

    /**
     * @see     https://help.shopify.com/api/reference/product#update
     * @param   Product|array       $request
     * @return  Product
     */
    public function update ($request = [])
    {
        $request                        = $request instanceof Product ? $request : new Product($request);
        $response                       = parent::makeHttpRequest('put', '/products/' . $request->getId() . '.json', ['product' => $request->jsonSerialize()]);
        return new Product($response['product']);
    }

    /**
     * @see     https://help.shopify.com/api/reference/product#destroy
     * @param   int         $id
     * @return  bool
     */
    public function delete ($id)
    {
        $response                       = parent::makeHttpRequest('delete', '/products/' . $id . '.json');
        return true;
    }

    /**
     * @see     https://help.shopify.com/api/reference/product_variant#index
     * @param   int         $id
     * @return  Variant[]
     */
    public function getVariants ($id)
    {
        $response                       = parent::makeHttpRequest('get', '/products/' . $id . '/variants.json');
        $items                          = AU::get($response['variants'], []);

        $result                         = [];
        foreach ($items AS $variant)
        {
            $result[]                   = new Variant($variant);
        }

        return $result;
    }

}