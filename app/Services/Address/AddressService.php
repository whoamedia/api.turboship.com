<?php

namespace App\Services\Address;


use App\Http\Requests\Addresses\UpdateAddress;
use App\Models\Locations\Address;
use App\Models\Locations\Validation\CountryValidation;
use App\Models\Locations\Validation\SubdivisionValidation;

class AddressService
{

    /**
     * @var CountryValidation
     */
    private $countryValidation;

    /**
     * @var SubdivisionValidation
     */
    private $subdivisionValidation;

    public function __construct()
    {
        $this->countryValidation            = new CountryValidation();
        $this->subdivisionValidation        = new SubdivisionValidation();
    }

    /**
     * @param   Address $address
     * @param   UpdateAddress $updateAddress
     * @return  Address
     */
    public function updateAddress(Address $address, UpdateAddress $updateAddress)
    {
        if (!is_null($updateAddress->getFirstName()))
            $address->setFirstName($updateAddress->getFirstName());

        if (!is_null($updateAddress->getLastName()))
            $address->setLastName($updateAddress->getLastName());

        if (!is_null($updateAddress->getCompany()))
            $address->setCompany($updateAddress->getCompany());

        if (!is_null($updateAddress->getStreet1()))
            $address->setStreet1($updateAddress->getStreet1());

        if (!is_null($updateAddress->getStreet2()))
            $address->setStreet2($updateAddress->getStreet2());

        if (!is_null($updateAddress->getCity()))
            $address->setCity($updateAddress->getCity());

        if (!is_null($updateAddress->getPostalCode()))
            $address->setPostalCode($updateAddress->getPostalCode());

        if (!is_null($updateAddress->getSubdivisionId()))
        {
            $subdivision                    = $this->subdivisionValidation->idExists($updateAddress->getSubdivisionId());
            $address->setSubdivision($subdivision);
        }

        if (!is_null($updateAddress->getCountryId()))
        {
            $country                        = $this->countryValidation->idExists($updateAddress->getCountryId());
            $address->setCountry($country);
        }

        if (!is_null($updateAddress->getPhone()))
            $address->setPhone($updateAddress->getPhone());

        if (!is_null($updateAddress->getEmail()))
            $updateAddress->setEmail($updateAddress->getEmail());

        return $address;
    }
}