<?php

namespace App\Models\Integrations;


use App\Models\Shipments\Service;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShippingApiService implements \JsonSerializable
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
     * @var ShippingApiCarrier
     */
    protected $shippingApiCarrier;

    /**
     * @var Service
     */
    protected $service;


    public function __construct($data = [])
    {
        $this->name                         = AU::get($data['name']);
        $this->shippingApiCarrier           = AU::get($data['shippingApiCarrier']);
        $this->service                      = AU::get($data['service']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                       = $this->id;
        $object['name']                     = $this->name;
        $object['shippingApiCarrier']       = $this->shippingApiCarrier->jsonSerialize();
        $object['service']                  = $this->service->jsonSerialize();

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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return ShippingApiCarrier
     */
    public function getShippingApiCarrier()
    {
        return $this->shippingApiCarrier;
    }

    /**
     * @param ShippingApiCarrier $shippingApiCarrier
     */
    public function setShippingApiCarrier($shippingApiCarrier)
    {
        $this->shippingApiCarrier = $shippingApiCarrier;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

}