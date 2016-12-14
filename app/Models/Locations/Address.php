<?php

namespace App\Models\Locations;


use jamesvweston\Utilities\ArrayUtil AS AU;

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
     * @var     Subdivision
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
            $this->phone                = AU::get($data['phone']);
            $this->email                = AU::get($data['email']);
            $this->subdivision          = AU::get($data['subdivision']);
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
        $object['subdivision']          = $this->subdivision->jsonSerialize();
        $object['phone']                = $this->getPhone();
        $object['email']                = $this->getEmail();

        return $object;
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Subdivision
     */
    public function getSubdivision()
    {
        return $this->subdivision;
    }

    /**
     * @param Subdivision $subdivision
     */
    public function setSubdivision($subdivision)
    {
        $this->subdivision = $subdivision;
    }

}