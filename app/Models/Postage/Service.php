<?php

namespace App\Models\Postage;


class Service implements \JsonSerializable
{

    /**
     * @var     int
     */
    protected $id;

    /**
     * @var     string
     */
    protected $name;

    /**
     * @var     Carrier
     */
    protected $carrier;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['carrier']              = $this->carrier->jsonSerialize();

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

}