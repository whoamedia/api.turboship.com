<?php

namespace App\Services\Address;


use App\Exceptions\Address\AddressNotFoundException;
use App\Exceptions\Address\InvalidCityException;
use App\Exceptions\Address\InvalidSubdivisionException;
use App\Exceptions\Address\MultipleAddressesFoundException;
use App\Exceptions\Address\USPSApiErrorException;
use App\Models\Locations\Address;
use App\Repositories\Doctrine\Locations\SubdivisionRepository;
use App\Utilities\CountryUtility;
use GuzzleHttp\Client;
use Log;
use EntityManager;

class USPSAddressService
{

    /**
     * @var string
     */
    protected $devurl;

    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $userid;

    /**
     * @var Client
     */
    protected $guzzle;

    /**
     * @var SubdivisionRepository
     */
    protected $subdivisionRepo;

    public function __construct()
    {
        $this->devurl                   = "http://production.shippingapis.com/ShippingAPI.dll";
        $this->service                  = "Verify";
        $this->userid                   = "842ATCOS7827";   //  789NUWEA6459    842ATCOS7827

        $this->guzzle                   = new Client();

        $this->subdivisionRepo          = EntityManager::getRepository('App\Models\Locations\Subdivision');
    }

    /**
     * @param Address $address
     * @return Address
     * @throws USPSApiErrorException|InvalidCityException|InvalidSubdivisionException|AddressNotFoundException|MultipleAddressesFoundException
     */
    public function validateAddress(Address $address)
    {
        $subdivisionName                = is_null($address->getSubdivision()) ? $address->getStateProvince() : $address->getSubdivision()->getName();
        $xml = rawurlencode('<AddressValidateRequest USERID="' . $this->userid . '">
                        <Address ID="0">
                            <Address1>' . $address->getStreet2() . '</Address1>
                            <Address2>' . $address->getStreet1() . '</Address2>
                            <City>' . $address->getCity() . '</City>
                            <State>' . $subdivisionName . '</State>
                            <Zip5>' . $address->getPostalCode() . '</Zip5>
                            <Zip4></Zip4>
                        </Address>
                    </AddressValidateRequest>');

        $request                        = $this->devurl . "?API=" . $this->service . "&xml=" . $xml;
        $response                       = $this->guzzle->get($request);
        $response                       = $response->getBody()->getContents();

        $dom                            = new \DOMDocument();

        if (is_null($response) || empty($response))
        {
            throw new USPSApiErrorException('No response from address validation service');
        }

        $dom->loadXML($response);

        if (!$dom)
            exit;

        $s                              = simplexml_import_dom($dom);

        if (isset($s->Address[0]->Error[0]->Description) || isset($s->Description))
        {
            if (isset($s->Description))
            {
                Log::error($s->Description);
                throw new USPSApiErrorException($s->Description);

            }
            else
            {
                $error_msg              = $s->Address[0]->Error[0]->Description;

                if (preg_match("/Invalid City/", $error_msg))
                    throw new InvalidCityException();
                else if (preg_match("/Invalid State/", $error_msg))
                    throw new InvalidSubdivisionException();
                else if (preg_match("/Address Not Found/", $error_msg))
                    throw new AddressNotFoundException();
                else if (preg_match("/Multiple addresses were found/", $error_msg))
                    throw new MultipleAddressesFoundException();
                else
                    throw new USPSApiErrorException();
            }
        }

        $street1                        = ucwords(strtolower($s->Address[0]->Address2));
        $street2                        = '';

        if (isset($s->Address[0]->Address1))
            $street2                    = ucwords(strtolower($s->Address[0]->Address1));

        $city                           = ucwords(strtolower($s->Address[0]->City));
        $subdivisionName                = (string)$s->Address[0]->State;
        $postalCode                     = ucwords(strtolower($s->Address[0]->Zip5));

        // It's common for the USPS to repond with empty values. This attempts to avoid setting those values
        if (trim($street1) == "" || trim($city) == "" || trim($subdivisionName) == "" || trim($postalCode) == "")
            throw new USPSApiErrorException('Received empty response from USPS address validation');

        $address->setStreet1($street1);
        if (!empty($street2))
            $address->setStreet2($street2);
        $address->setCity($city);
        $address->setPostalCode($postalCode);

        $subdivision                = $this->subdivisionRepo->getOneByWildCard($subdivisionName, CountryUtility::UNITED_STATES);
        if (is_null($subdivision))
            echo $subdivisionName . PHP_EOL;

        $address->setSubdivision($subdivision);
        return $address;
    }

}