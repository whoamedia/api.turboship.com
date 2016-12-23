<?php

namespace App\Integrations\EasyPost\Api;


use App\Integrations\EasyPost\Models\Responses\EasyPostCarrierType;

class CarrierTypeApi extends BaseApi
{

    /**
     * @var string
     */
    protected $path = '/carrier_types';


    /**
     * PRODUCTION ONLY
     * @see https://www.easypost.com/docs/api.html#retrieve-available-carrier-types
     * @return  EasyPostCarrierType[]
     */
    public function getAvailable ()
    {
        $response                       = parent::makeHttpRequest('get', $this->path);

        $result                         = [];
        foreach ($response AS $carrierType)
        {
            $result[]                   = new EasyPostCarrierType($carrierType);
        }

        return $result;
    }
}