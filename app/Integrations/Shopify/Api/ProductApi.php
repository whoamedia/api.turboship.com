<?php

namespace App\Integrations\Shopify\Api;


use App\Integrations\Shopify\Models\Requests\CreateShopifyProduct;
use App\Integrations\Shopify\Models\Requests\GetShopifyProductCount;
use App\Integrations\Shopify\Models\Requests\GetShopifyProducts;
use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Integrations\Shopify\Models\Responses\ShopifyVariant;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ProductApi extends BaseApi
{

    /**
     * @see     https://help.shopify.com/api/reference/product#index
     * @param   GetShopifyProducts|array        $request
     * @return  ShopifyProduct[]
     */
    public function get ($request = [])
    {
        $request                        = $request instanceof GetShopifyProducts ? $request : new GetShopifyProducts($request);
        $response                       = parent::makeHttpRequest('get', '/products.json', $request);
        $items                          = AU::get($response['products'], []);

        $result                         = [];
        foreach ($items AS $product)
        {
            $result[]                   = new ShopifyProduct($product);
        }

        return $result;
    }

    /**
     * @see     https://help.shopify.com/api/reference/product#show
     * @param   int         $id
     * @return  ShopifyProduct|null
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', '/products/' . $id . '.json');

        $items                          = AU::get($response['product']);
        return is_null($items) ? null : new ShopifyProduct($items);
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
     * @return  ShopifyProduct
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateShopifyProduct ? $request : new CreateShopifyProduct($request);
        $response                       = parent::makeHttpRequest('post', '/products.json', ['product' => $request->jsonSerialize()]);
        return new ShopifyProduct($response['product']);
    }

    /**
     * @see     https://help.shopify.com/api/reference/product#update
     * @param   ShopifyProduct|array       $request
     * @return  ShopifyProduct
     */
    public function update ($request = [])
    {
        $request                        = $request instanceof ShopifyProduct ? $request : new ShopifyProduct($request);
        $response                       = parent::makeHttpRequest('put', '/products/' . $request->getId() . '.json', ['product' => $request->jsonSerialize()]);
        return new ShopifyProduct($response['product']);
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
     * @return  ShopifyVariant[]
     */
    public function getVariants ($id)
    {
        $response                       = parent::makeHttpRequest('get', '/products/' . $id . '/variants.json');
        $items                          = AU::get($response['variants'], []);

        $result                         = [];
        foreach ($items AS $variant)
        {
            $result[]                   = new ShopifyVariant($variant);
        }

        return $result;
    }

}