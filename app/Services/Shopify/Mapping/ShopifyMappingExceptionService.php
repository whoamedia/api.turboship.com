<?php

namespace App\Services\Shopify\Mapping;


use App\Models\CMS\Client;

class ShopifyMappingExceptionService
{

    /**
     * @param   Client $client
     * @param   $variantSku
     * @param   $variantTitle
     * @return  string
     */
    public function getShopifySku (Client $client, $variantSku, $variantTitle)
    {
        //  Modify the sku that Whoa Media (clientId 1) sends
        if ($client->getId() == 1)
            return $variantSku . '__' . $variantTitle;
        else
            return $variantSku;
    }

}