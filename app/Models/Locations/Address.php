<?php

namespace App\Models\Locations;


use App\Utilities\SubdivisionTypeUtility;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @SWG\Definition(@SWG\Xml())
 */
class Address implements \JsonSerializable
{

    /**
     * @SWG\Property(example="1")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="John")
     * @var     string
     */
    protected $firstName;

    /**
     * @SWG\Property(example="Doe")
     * @var     string
     */
    protected $lastName;

    /**
     * @SWG\Property(example="ABC Company")
     * @var     string|null
     */
    protected $company;

    /**
     * @SWG\Property(example="24 Wherever St.")
     * @var     string
     */
    protected $street1;

    /**
     * @SWG\Property(example="#41")
     * @var     string
     */
    protected $street2;

    /**
     * @SWG\Property(example="Savannah")
     * @var     string|null
     */
    protected $city;

    /**
     * @SWG\Property(example="31401")
     * @var     string|null
     */
    protected $postalCode;

    /**
     * @SWG\Property(example="GA")
     * @var     string|null
     */
    protected $stateProvince;

    /**
     * @SWG\Property(example="US")
     * @var     string|null
     */
    protected $countryCode;

    /**
     * @SWG\Property(example="111-111-1111")
     * @var     string|null
     */
    protected $phone;

    /**
     * @SWG\Property(example="john.doe@gmail.com")
     * @var     string|null
     */
    protected $email;

    /**
     * @SWG\Property()
     * @var     \DateTime
     */
    protected $createdAt;

    /**
     * @SWG\Property()
     * @var     Country|null
     */
    protected $country;

    /**
     * @SWG\Property()
     * @var     Subdivision|null
     */
    protected $subdivision;


    /**
     * Address constructor.
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        $this->createdAt                = new \DateTime();

        if (is_array($data))
        {
            $this->firstName            = AU::get($data['firstName']);
            $this->lastName             = AU::get($data['lastName']);
            $this->company              = AU::get($data['company']);
            $this->street1              = AU::get($data['street1']);
            $this->street2              = AU::get($data['street2']);
            $this->city                 = AU::get($data['city']);
            $this->postalCode           = AU::get($data['postalCode']);
            $this->stateProvince        = AU::get($data['stateProvince']);
            $this->countryCode          = AU::get($data['countryCode']);
            $this->phone                = AU::get($data['phone']);
            $this->email                = AU::get($data['email']);
            $this->country              = AU::get($data['country']);
            $this->subdivision          = AU::get($data['subdivision']);

            if (!is_null($this->phone))
                $this->setPhone($this->phone);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['firstName']            = $this->getFirstName();
        $object['lastName']             = $this->getLastName();
        $object['company']              = $this->getCompany();
        $object['street1']              = $this->getStreet1();
        $object['street2']              = $this->getStreet2();
        $object['city']                 = $this->getCity();
        $object['postalCode']           = $this->getPostalCode();
        $object['countryCode']          = $this->countryCode;
        $object['country']              = is_null($this->country) ? null : $this->country->jsonSerialize();
        $object['stateProvince']        = $this->stateProvince;
        $object['subdivision']          = is_null($this->subdivision) ? null : $this->subdivision->jsonSerialize();
        $object['phone']                = $this->getPhone();
        $object['email']                = $this->getEmail();

        return $object;
    }

    /**
     * @return Address
     */
    public function duplicate ()
    {
        $address                        = new Address($this->jsonSerialize());
        $address->setCountry($this->country);
        $address->setSubdivision($this->subdivision);

        return $address;
    }


    public function validate ()
    {
        if (!is_null($this->subdivision) && !is_null($this->country))
        {
            if ($this->subdivision->getCountry()->getId() != $this->country->getId())
                throw new BadRequestHttpException('Subdivision does not belong to country');
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
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
     * @return string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @param string $street1
     */
    public function setStreet1($street1)
    {
        $this->street1 = $street1;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @param string $street2
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
    public function getStateProvince()
    {
        return $this->stateProvince;
    }

    /**
     * @param null|string $stateProvince
     */
    public function setStateProvince($stateProvince)
    {
        $this->stateProvince = $stateProvince;
    }

    /**
     * @return null|string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param null|string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
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
        $this->phone = substr($phone, 0, 19);
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Country|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country|null $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Subdivision|null
     */
    public function getSubdivision()
    {
        return $this->subdivision;
    }

    /**
     * @param Subdivision|null $subdivision
     */
    public function setSubdivision($subdivision)
    {
        $this->subdivision = $subdivision;
    }

    /**
     * Does this address require customs if it is shipping from a US location ?
     * @return bool
     */
    public function requiresUSCustoms ()
    {
        if ($this->country->getIso2() != 'US')
            return true;
        else if (is_null($this->subdivision))
            return false;
        else if ($this->subdivision->getSubdivisionType()->getId() == SubdivisionTypeUtility::ARMED_FORCES)
            return true;
        else
            return false;
    }

}