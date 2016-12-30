<?php

namespace App\Models\Locations;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Location implements \JsonSerializable
{

    /**
     * @SWG\Property(example="1")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="24 East Liberty Street")
     * @var     string
     */
    protected $street1;

    /**
     * @SWG\Property(example="#41")
     * @var     string|null
     */
    protected $street2;

    /**
     * @SWG\Property(example="San Diego")
     * @var     string
     */
    protected $city;

    /**
     * @SWG\Property(example="91932")
     * @var     string
     */
    protected $postalCode;

    /**
     * @SWG\Property()
     * @var     Subdivision
     */
    protected $subdivision;

    /**
     * @SWG\Property()
     * @var    \DateTime
     */
    protected $createdAt;


    public function __construct ($data = null)
    {
        $this->createdAt                = new \DateTime();

        if (is_array($data))
        {
            $this->street1              = AU::get($data['street1']);
            $this->street2              = AU::get($data['street2']);
            $this->city                 = AU::get($data['city']);
            $this->postalCode           = AU::get($data['postalCode']);
            $this->subdivision          = AU::get($data['subdivision']);
        }
    }

    public function validate()
    {

    }

    /**
     * @return  array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->getId();
        $object['street1']              = $this->getStreet1();
        $object['street2']              = $this->getStreet2();
        $object['city']                 = $this->getCity();
        $object['postalCode']           = $this->getPostalCode();
        $object['subdivision']          = $this->subdivision->jsonSerialize();

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
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

}