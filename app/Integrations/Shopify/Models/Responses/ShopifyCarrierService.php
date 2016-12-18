<?php

namespace App\Integrations\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyCarrierService implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * The name of the shipping service as seen by merchants and their customers.
     * @var string
     */
    protected $name;

    /**
     * States whether or not this carrier service is active
     * @var bool
     */
    protected $active;

    /**
     * States if merchants are able to send dummy data to your service through the Shopify admin to see shipping rate examples
     * @var bool
     */
    protected $service_discovery;

    /**
     * Distinguishes between api or legacy carrier services.
     * @var string
     */
    protected $carrier_service_type;

    /**
     * The format of the data returned by the URL endpoint.
     * Valid values are "json" and "xml"
     * If a format is not specified, it will default to json.
     * @var string
     */
    protected $format;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->name                     = AU::get($data['name']);
        $this->active                   = AU::get($data['active']);
        $this->service_discovery        = AU::get($data['service_discovery']);
        $this->carrier_service_type     = AU::get($data['carrier_service_type']);
        $this->format                   = AU::get($data['format']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['active']               = $this->active;
        $object['service_discovery']    = $this->service_discovery;
        $object['carrier_service_type'] = $this->carrier_service_type;
        $object['format']               = $this->format;

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
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return boolean
     */
    public function isServiceDiscovery()
    {
        return $this->service_discovery;
    }

    /**
     * @param boolean $service_discovery
     */
    public function setServiceDiscovery($service_discovery)
    {
        $this->service_discovery = $service_discovery;
    }

    /**
     * @return string
     */
    public function getCarrierServiceType()
    {
        return $this->carrier_service_type;
    }

    /**
     * @param string $carrier_service_type
     */
    public function setCarrierServiceType($carrier_service_type)
    {
        $this->carrier_service_type = $carrier_service_type;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }
}