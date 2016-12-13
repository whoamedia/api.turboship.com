<?php

namespace App\Models\Locations;


/**
 * @SWG\Definition(@SWG\Xml())
 */
class SubdivisionAltName implements \JsonSerializable
{

    /**
     * @SWG\Property(example="1")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="Caleefornia")
     * @var     string
     */
    protected $name;

    /**
     * @SWG\Property()
     * @var     Subdivision
     */
    protected $subdivision;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['subdivision']          = $this->getSubdivision()->jsonSerialize();

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
     * @return Subdivision
     */
    public function getSubdivision()
    {
        return $this->subdivision;
    }

}