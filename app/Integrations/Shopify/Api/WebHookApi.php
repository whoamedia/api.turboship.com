<?php

namespace App\Integrations\Shopify\Api;


use App\Integrations\Shopify\Models\Requests\CreateShopifyWebHook;
use App\Integrations\Shopify\Models\Requests\GetShopifyWebHooks;
use App\Integrations\Shopify\Models\Responses\ShopifyWebHook;
use jamesvweston\Utilities\ArrayUtil AS AU;

class WebHookApi extends BaseApi
{

    /**
     * @see     https://help.shopify.com/api/reference/webhook#index
     * @param   GetShopifyWebHooks|array        $request
     * @return  ShopifyWebHook[]
     */
    public function get ($request = [])
    {
        $request                        = $request instanceof GetShopifyWebHooks ? $request : new GetShopifyWebHooks($request);
        $response                       = parent::makeHttpRequest('get', '/webhooks.json', $request);
        $items                          = AU::get($response['webhooks'], []);

        $result                         = [];
        foreach ($items AS $webHook)
        {
            $result[]                   = new GetShopifyWebHooks($webHook);
        }

        return $result;
    }

    /**
     * @see     https://help.shopify.com/api/reference/webhook#show
     * @param   int         $id
     * @return  ShopifyWebHook|null
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', '/webhooks/' . $id . '.json');

        $items                          = AU::get($response['webhook']);
        return is_null($items) ? null : new ShopifyWebHook($items);
    }

    /**
     * @see     https://help.shopify.com/api/reference/webhook#create
     * @param   CreateShopifyWebHook|array       $request
     * @return  ShopifyWebHook
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateShopifyWebHook ? $request : new CreateShopifyWebHook($request);
        $response                       = parent::makeHttpRequest('post', '/webhooks.json', ['webhook' => $request->jsonSerialize()]);
        return new ShopifyWebHook($response['webhook']);
    }


    public function delete ($id)
    {
        $response                       = parent::makeHttpRequest('delete', '/webhooks/' . $id . '.json');
        return true;
    }


}