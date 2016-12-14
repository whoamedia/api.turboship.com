<?php

namespace App\Models\Locations;


use Doctrine\Common\Collections\ArrayCollection;

/**
 * @SWG\Definition(@SWG\Xml())
 */
class Continent implements \JsonSerializable
{

    /**
     * @SWG\Property(example="5")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="North America")
     * @var     string
     */
    protected $name;

    /**
     * @SWG\Property(example="NAM")
     * @var     string
     */
    protected $symbol;

    /**
     * @var ArrayCollection
     */
    protected $countries;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        $object['symbol']               = $this->getSymbol();

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
     * @return Country[]
     */
    public function getCountries()
    {
        return $this->countries->toArray();
    }

}