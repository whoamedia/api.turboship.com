<?php

namespace App\Services\Address;


use App\Exceptions\Country\InvalidCountryException;
use App\Exceptions\Country\InvalidSubdivisionException;
use App\Models\Locations\Address;
use App\Models\Locations\ProvidedAddress;
use App\Repositories\Doctrine\Locations\CountryRepository;
use App\Repositories\Doctrine\Locations\SubdivisionRepository;
use EntityManager;

class ProvidedAddressService
{

    /**
     * @var CountryRepository
     */
    private $countryRepo;

    /**
     * @var SubdivisionRepository
     */
    private $subdivisionRepo;


    public function __construct()
    {
        $this->countryRepo              = EntityManager::getRepository('App\Models\Locations\Country');
        $this->subdivisionRepo          = EntityManager::getRepository('App\Models\Locations\Subdivision');
    }


    public function toAddress (ProvidedAddress $providedAddress)
    {
        $address                        = new Address();

        if (is_null($providedAddress->getCountry()) || empty(trim($providedAddress->getCountry())))
            throw new InvalidCountryException();

        $country                    = $this->countryRepo->getOneByWildCard($providedAddress->getCountry());
        if (is_null($country))
            throw new InvalidCountryException();


        $subdivision                = $this->subdivisionRepo->getOneByWildCard($providedAddress->getSubdivision(), $country->getIso2());
        if (is_null($subdivision))
            throw new InvalidSubdivisionException();


        $address->setSubdivision($subdivision);
        $address->setFirstName($providedAddress->getFirstName());
        $address->setLastName($providedAddress->getLastName());
        $address->setCompany($providedAddress->getCompany());
        $address->setStreet1($providedAddress->getStreet1());
        $address->setStreet2($providedAddress->getStreet2());
        $address->setCity($providedAddress->getCity());
        $address->setPostalCode($providedAddress->getPostalCode());
        $address->setPhone($providedAddress->getPhone());
        $address->setEmail($providedAddress->getEmail());


    }
}