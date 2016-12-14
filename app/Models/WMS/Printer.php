<?php

namespace App\Models\WMS;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Printer
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
     * @var FulfillmentCenter
     */
    protected $fulfillmentCenter;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->fulfillmentCenter        = AU::get($data['fulfillmentCenter']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        $object['fulfillmentCenter']    = $this->fulfillmentCenter->jsonSerialize();

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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return FulfillmentCenter
     */
    public function getFulfillmentCenter()
    {
        return $this->fulfillmentCenter;
    }

    /**
     * @param FulfillmentCenter $fulfillmentCenter
     */
    public function setFulfillmentCenter($fulfillmentCenter)
    {
        $this->fulfillmentCenter = $fulfillmentCenter;
    }


}