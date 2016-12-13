<?php

namespace App\Models\Locations;


use Doctrine\Common\Collections\ArrayCollection;

/**
 * @SWG\Definition(@SWG\Xml())
 */
class Country implements \JsonSerializable
{

    /**
     * @SWG\Property(example="233")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="United States")
     * @var     string
     */
    protected $name;

    /**
     * @SWG\Property(example="US")
     * @var     string
     */
    protected $iso2;

    /**
     * @var     string
     */
    protected $iso3;

    /**
     * @var     string
     */
    protected $isoNumeric;

    /**
     * @var     string
     */
    protected $fipsCode;

    /**
     * @var     string
     */
    protected $capital;

    /**
     * @var     bool
     */
    protected $isEU;

    /**
     * @var     bool
     */
    protected $isUK;

    /**
     * @var     bool
     */
    protected $isUSTerritory;

    /**
     * @SWG\Property()
     * @var     Continent
     */
    protected $continent;

    /**
     * @var ArrayCollection
     */
    protected $subdivisions;

    /**
     * @var ArrayCollection
     */
    protected $postalDistricts;


    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['iso2']                 = $this->iso2;
        $object['continent']            = $this->continent->jsonSerialize();

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * @return string
     */
    public function getIsoNumeric()
    {
        return $this->isoNumeric;
    }

    /**
     * @return string
     */
    public function getFipsCode()
    {
        return $this->fipsCode;
    }

    /**
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * @return boolean
     */
    public function isEU()
    {
        return $this->isEU;
    }

    /**
     * @return boolean
     */
    public function isUK()
    {
        return $this->isUK;
    }

    /**
     * @return boolean
     */
    public function isUSTerritory()
    {
        return $this->isUSTerritory;
    }

    /**
     * @return Continent
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * @return Subdivision[]
     */
    public function getSubdivisions()
    {
        return $this->subdivisions->toArray();
    }

    /**
     * @return PostalDistrict[]
     */
    public function getPostalDistricts()
    {
        return $this->postalDistricts->toArray();
    }
}