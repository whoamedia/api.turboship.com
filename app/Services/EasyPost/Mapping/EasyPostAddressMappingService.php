<?php

namespace App\Services\EasyPost\Mapping;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostAddress;
use App\Models\Locations\Address;

class EasyPostAddressMappingService extends BaseEasyPostMappingService
{

    /**
     * @param   Address $address
     * @return  CreateEasyPostAddress
     */
    public function toEasyPostAddress (Address $address)
    {
        $easyPostAddress                = new CreateEasyPostAddress();
        $easyPostAddress->setStreet1($address->getStreet1());
        $easyPostAddress->setStreet2($address->getStreet2());
        $easyPostAddress->setCity($address->getCity());

        $state                          = !is_null($address->getSubdivision()) ? $address->getSubdivision()->getName() : $address->getStateProvince();
        $easyPostAddress->setState($state);
        $easyPostAddress->setZip($address->getPostalCode());
        $easyPostAddress->setCountry($address->getCountry()->getIso2());
        $easyPostAddress->setName($address->getFirstName() . ' ' . $address->getLastName());
        $easyPostAddress->setCompany($address->getCompany());
        $easyPostAddress->setPhone($address->getPhone());
        $easyPostAddress->setEmail($address->getEmail());

        return $easyPostAddress;
    }
}