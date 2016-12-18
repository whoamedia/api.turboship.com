<?php

namespace App\Services\Shopify\Api;


use App\Services\Shopify\Models\Responses\ShopifyCarrierService;
use jamesvweston\Utilities\ArrayUtil AS AU;

class CarrierServiceApi extends BaseApi
{

    /**
     * @see     https://help.shopify.com/api/reference/carrierservice#index
     * @return  ShopifyCarrierService[]
     */
    public function get ()
    {
        $response                       = parent::makeHttpRequest('get', '/carrier_services.json');
        $items                          = AU::get($response['carrier_services'], []);

        $result                         = [];
        foreach ($items AS $collect)
        {
            $result[]                   = new ShopifyCarrierService($collect);
        }

        return $result;
    }

    /**
     * @see     https://help.shopify.com/api/reference/carrierservice#show
     * @param   int         $id
     * @return  ShopifyCarrierService|null
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', '/carrier_services/' . $id . '.json');

        $items                          = AU::get($response['carrier_service']);
        return is_null($items) ? null : new ShopifyCarrierService($items);
    }

}