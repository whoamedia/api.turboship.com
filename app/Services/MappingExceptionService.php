<?php

namespace App\Services;


use App\Models\CMS\Client;

class MappingExceptionService
{

    /**
     * @param   Client $client
     * @param   $variantSku
     * @param   $externalVariantId
     * @return  string
     */
    public function getShopifySku (Client $client, $variantSku, $externalVariantId)
    {
        //  Modify the sku that Whoa Media (clientId 1) sends
        if ($client->getId() == 1)
            return $externalVariantId;
        else
            return $variantSku;
    }

}