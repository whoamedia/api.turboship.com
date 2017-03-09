<?php

namespace App\Models\Locations;


use Doctrine\Common\Collections\ArrayCollection;

/**
 * @SWG\Definition(@SWG\Xml())
 */
class Subdivision implements \JsonSerializable
{

    /**
     * @SWG\Property(example="4658")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="California")
     * @var     string
     */
    protected $name;

    /**
     * @SWG\Property(example="US-CA")
     * @var     string
     */
    protected $symbol;

    /**
     * @SWG\Property(example="CA")
     * @var     string
     */
    protected $localSymbol;

    /**
     * @SWG\Property()
     * @var Country
     */
    protected $country;

    /**
     * @SWG\Property()
     * @var SubdivisionType
     */
    protected $subdivisionType;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['symbol']               = $this->symbol;
        $object['localSymbol']          = $this->localSymbol;
        $object['subdivisionType']      = $this->subdivisionType->jsonSerialize();
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
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getLocalSymbol()
    {
        return $this->localSymbol;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return SubdivisionType
     */
    public function getSubdivisionType()
    {
        return $this->subdivisionType;
    }

}