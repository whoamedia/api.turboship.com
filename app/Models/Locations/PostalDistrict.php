<?php

namespace App\Models\Locations;


use Doctrine\Common\Collections\ArrayCollection;

/**
 * @SWG\Definition(@SWG\Xml())
 */
class PostalDistrict implements \JsonSerializable
{

    /**
     * @SWG\Property(example="1")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="Newfoundland and Labrador")
     * @var     string
     */
    protected $name;

    /**
     * @SWG\Property(example="Terre-Neuve et Labrador")
     * @var     string
     */
    protected $french;

    /**
     * @SWG\Property(example="A")
     * @var     string
     */
    protected $symbol;

    /**
     * @SWG\Property()
     * @var Country
     */
    protected $country;

    /**
     * @var ArrayCollection
     */
    protected $postalDistrictSubdivisions;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['french']               = $this->french;
        $object['symbol']               = $this->symbol;
        $object['country']              = $this->country->jsonSerialize();

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
    public function getFrench()
    {
        return $this->french;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return PostalDistrictSubdivision[]
     */
    public function getPostalDistrictSubdivisions()
    {
        return $this->postalDistrictSubdivisions->toArray();
    }
}