<?php

namespace App\Integrations\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyAddress implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $address1;

    /**
     * @var string|null
     */
    protected $address2;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string
     */
    protected $province;

    /**
     * @var string
     */
    protected $province_code;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $country_code;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $latitude;

    /**
     * @var string|null
     */
    protected $longitude;


    public function __construct($data = [])
    {
        $this->first_name               = AU::get($data['first_name']);
        $this->last_name                = AU::get($data['last_name']);
        $this->name                     = AU::get($data['name']);
        $this->company                  = AU::get($data['company']);
        $this->address1                 = AU::get($data['address1']);
        $this->address2                 = AU::get($data['address2']);
        $this->city                     = AU::get($data['city']);
        $this->province                 = AU::get($data['province']);
        $this->province_code            = AU::get($data['province_code']);
        $this->zip                      = AU::get($data['zip']);
        $this->country                  = AU::get($data['country']);
        $this->country_code             = AU::get($data['country_code']);
        $this->phone                    = AU::get($data['phone']);
        $this->latitude                 = AU::get($data['latitude']);
        $this->longitude                = AU::get($data['longitude']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['first_name']           = $this->first_name;
        $object['last_name']            = $this->last_name;
        $object['name']                 = $this->name;
        $object['company']              = $this->company;
        $object['address1']             = $this->address1;
        $object['address2']             = $this->address2;
        $object['city']                 = $this->city;
        $object['province']             = $this->province;
        $object['province_code']        = $this->province_code;
        $object['zip']                  = $this->zip;
        $object['country']              = $this->country;
        $object['country_code']         = $this->country_code;
        $object['phone']                = $this->phone;
        $object['latitude']             = $this->latitude;
        $object['longitude']            = $this->longitude;

        return $object;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    /**
     * @return null|string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param null|string $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getProvinceCode()
    {
        return $this->province_code;
    }

    /**
     * @param string $province_code
     */
    public function setProvinceCode($province_code)
    {
        $this->province_code = $province_code;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
    }

    /**
     * @return null|string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return null|string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param null|string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return null|string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param null|string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

}