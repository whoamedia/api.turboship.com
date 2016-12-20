<?php

namespace App\Models\Shipments;


class Service implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Carrier
     */
    protected $carrier;

    /**
     * @var bool
     */
    protected $isDomestic;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['carrier']              = $this->carrier->jsonSerialize();
        $object['isDomestic']           = $this->isDomestic;

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
     * @return Carrier
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @return boolean
     */
    public function isDomestic()
    {
        return $this->isDomestic;
    }

    /**
     * @param boolean $isDomestic
     */
    public function setIsDomestic($isDomestic)
    {
        $this->isDomestic = $isDomestic;
    }

}