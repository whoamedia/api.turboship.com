<?php

namespace App\Models\Locations;


/**
 * @SWG\Definition(@SWG\Xml())
 */
class PostalDistrictSubdivision implements \JsonSerializable
{

    /**
     * @SWG\Property(example="1")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property()
     * @var     PostalDistrict
     */
    protected $postalDistrict;

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
        $object['postalDistrict']       = $this->postalDistrict->jsonSerialize();
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
     * @return PostalDistrict
     */
    public function getPostalDistrict()
    {
        return $this->postalDistrict;
    }

    /**
     * @return Subdivision
     */
    public function getSubdivision()
    {
        return $this->subdivision;
    }
}