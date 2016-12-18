<?php

namespace App\Services\Shopify\Api;


use App\Services\Shopify\Models\Requests\CreateShopifyCollect;
use App\Services\Shopify\Models\Requests\GetShopifyCollects;
use App\Services\Shopify\Models\Responses\Collect;
use jamesvweston\Utilities\ArrayUtil AS AU;

class CollectApi extends BaseApi
{

    /**
     * @see     https://help.shopify.com/api/reference/collect#index
     * @param   GetShopifyCollects|array        $request
     * @return  Collect[]
     */
    public function get ($request = [])
    {
        $request                        = $request instanceof GetShopifyCollects ? $request : new GetShopifyCollects($request);
        $response                       = parent::makeHttpRequest('get', '/collects.json', $request);
        $items                          = AU::get($response['collects'], []);

        $result                         = [];
        foreach ($items AS $collect)
        {
            $result[]                   = new Collect($collect);
        }

        return $result;
    }

    /**
     * @see     https://help.shopify.com/api/reference/collect#create
     * @param   CreateShopifyCollect|array       $request
     * @return  Collect
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateShopifyCollect ? $request : new CreateShopifyCollect($request);
        $response                       = parent::makeHttpRequest('post', '/collects.json', ['collect' => $request->jsonSerialize()]);
        return new Collect($response['collect']);
    }

    /**
     * @see     https://help.shopify.com/api/reference/collect#destroy
     * @param   int         $id
     * @return  bool
     */
    public function delete ($id)
    {
        $response                       = parent::makeHttpRequest('delete', '/collects/' . $id . '.json');
        return true;
    }

}