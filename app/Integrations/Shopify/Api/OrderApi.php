<?php

namespace App\Integrations\Shopify\Api;


use App\Integrations\Shopify\Models\Requests\CancelShopifyOrder;
use App\Integrations\Shopify\Models\Requests\GetShopifyOrders;
use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use jamesvweston\Utilities\ArrayUtil AS AU;

class OrderApi extends BaseApi
{

    /**
     * @see     https://help.shopify.com/api/reference/order#index
     * @param   GetShopifyOrders|array        $request
     * @return  ShopifyOrder[]
     */
    public function get ($request = [])
    {
        $request                        = $request instanceof GetShopifyOrders ? $request : new GetShopifyOrders($request);
        $response                       = parent::makeHttpRequest('get', '/orders.json', $request);
        $items                          = AU::get($response['orders'], []);

        $result                         = [];
        foreach ($items AS $order)
        {
            $result[]                   = new ShopifyOrder($order);
        }

        return $result;
    }

    /**
     * @see     https://help.shopify.com/api/reference/order#show
     * @param   int         $id
     * @return  ShopifyOrder()|null
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', '/orders/' . $id . '.json');

        $items                          = AU::get($response['order']);
        return is_null($items) ? null : new ShopifyOrder($items);
    }

    /**
     * @see     https://help.shopify.com/api/reference/order#close
     * @param   int         $id
     * @return  ShopifyOrder()|null
     */
    public function close ($id)
    {
        $response                       = parent::makeHttpRequest('post', '/orders/' . $id . '/close.json');

        $items                          = AU::get($response['order']);
        return is_null($items) ? null : new ShopifyOrder($items);
    }

    /**
     * @see     https://help.shopify.com/api/reference/order#open
     * @param   int         $id
     * @return  ShopifyOrder()|null
     */
    public function open ($id)
    {
        $response                       = parent::makeHttpRequest('post', '/orders/' . $id . '/open.json');

        $items                          = AU::get($response['order']);
        return is_null($items) ? null : new ShopifyOrder($items);
    }

    /**
     * @see     https://help.shopify.com/api/reference/order#cancel
     * @param   int         $id
     * @param   CancelShopifyOrder  $request
     * @return  ShopifyOrder()|null
     */
    public function cancel ($id, $request)
    {
        $request                        = $request instanceof GetShopifyOrders ? $request : new GetShopifyOrders($request);
        $response                       = parent::makeHttpRequest('post', '/orders/' . $id . '/cancel.json', $request);

        $items                          = AU::get($response['order']);
        return is_null($items) ? null : new ShopifyOrder($items);
    }


}