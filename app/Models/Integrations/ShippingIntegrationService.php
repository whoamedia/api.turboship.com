<?php

namespace App\Models\Integrations;


use App\Models\Shipments\Service;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShippingIntegrationService implements \JsonSerializable
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
     * @var ShippingIntegrationCarrier
     */
    protected $shippingIntegrationCarrier;

    /**
     * @var Service
     */
    protected $service;


    public function __construct($data = [])
    {
        $this->name                         = AU::get($data['name']);
        $this->shippingIntegrationCarrier   = AU::get($data['shippingIntegrationCarrier']);
        $this->service                      = AU::get($data['service']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                       = $this->id;
        $object['name']                     = $this->name;
        $object['shippingIntegrationCarrier']= $this->shippingIntegrationCarrier->jsonSerialize();
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
     * @return ShippingIntegrationCarrier
     */
    public function getShippingIntegrationCarrier()
    {
        return $this->shippingIntegrationCarrier;
    }

    /**
     * @param ShippingIntegrationCarrier $shippingIntegrationCarrier
     */
    public function setShippingIntegrationCarrier($shippingIntegrationCarrier)
    {
        $this->shippingIntegrationCarrier = $shippingIntegrationCarrier;
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