<?php

namespace App\Http\Requests\Addresses;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class CreateAddressRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $firstName;

    /**
     * @var string|null
     */
    protected $lastName;

    /**
     * @var string|null
     */
    protected $company;

    /**
     * @var string|null
     */
    protected $street1;

    /**
     * @var string|null
     */
    protected $street2;

    /**
     * @var string|null
     */
    protected $city;

    /**
     * @var string|null
     */
    protected $postalCode;

    /**
     * @var string|null
     */
    protected $subdivision;

    /**
     * @var string|null
     */
    protected $country;

    /**
     * @var string|null
     */
    protected $phone;

    /**
     * @var string|null
     */
    protected $email;


    public function __construct($data = [])
    {
        $this->firstName                = AU::get($data['firstName']);
        $this->lastName                 = AU::get($data['lastName']);
        $this->company                  = AU::get($data['company']);
        $this->street1                  = AU::get($data['street1']);
        $this->street2                  = AU::get($data['street2']);
        $this->city                     = AU::get($data['city']);
        $this->postalCode               = AU::get($data['postalCode']);
        $this->subdivision              = AU::get($data['subdivision']);
        $this->country                  = AU::get($data['country']);
        $this->phone                    = AU::get($data['phone']);
        $this->email                    = AU::get($data['email']);
    }

    public function validate()
    {
        if (is_null($this->firstName))
            throw new MissingMandatoryParametersException('firstName is required');

        if (is_null($this->lastName))
            throw new MissingMandatoryParametersException('lastName is required');

        if (is_null($this->street1))
            throw new MissingMandatoryParametersException('street1 is required');

        if (is_null($this->city))
            throw new MissingMandatoryParametersException('city is required');

        if (is_null($this->postalCode))
            throw new MissingMandatoryParametersException('postalCode is required');

        if (is_null($this->subdivision))
            throw new MissingMandatoryParametersException('subdivision is required');

        if (is_null($this->country))
            throw new MissingMandatoryParametersException('country is required');
    }

    public function clean ()
    {
        $this->ids                      = InputUtil::getIdsString($this->ids);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['firstName']            = $this->firstName;
        $object['lastName']             = $this->lastName;
        $object['company']              = $this->company;
        $object['street1']              = $this->street1;
        $object['street2']              = $this->street2;
        $object['city']                 = $this->city;
        $object['postalCode']           = $this->postalCode;
        $object['subdivision']          = $this->subdivision;
        $object['country']              = $this->country;
        $object['phone']                = $this->phone;
        $object['email']                = $this->email;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return null|string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param null|string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return null|string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @param null|string $street1
     */
    public function setStreet1($street1)
    {
        $this->street1 = $street1;
    }

    /**
     * @return null|string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @param null|string $street2
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;
    }

    /**
     * @return null|string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param null|string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return null|string
     */
    public function getSubdivision()
    {
        return $this->subdivision;
    }

    /**
     * @param null|string $subdivision
     */
    public function setSubdivision($subdivision)
    {
        $this->subdivision = $subdivision;
    }

    /**
     * @return null|string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param null|string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}