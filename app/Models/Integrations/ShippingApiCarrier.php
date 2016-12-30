<?php

namespace App\Models\Integrations;


use App\Models\Shipments\Carrier;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShippingApiCarrier implements \JsonSerializable
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
     * @var ShippingApiIntegration
     */
    protected $shippingApiIntegration;

    /**
     * @var Carrier
     */
    protected $carrier;

    /**
     * @var ArrayCollection
     */
    protected $shippingApiServices;


    public function __construct($data = [])
    {
        $this->shippingApiServices          = new ArrayCollection();

        $this->name                         = AU::get($data['name']);
        $this->shippingApiIntegration       = AU::get($data['shippingApiIntegration']);
        $this->carrier                      = AU::get($data['carrier']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                       = $this->id;
        $object['name']                     = $this->name;
        $object['carrier']                  = $this->carrier->jsonSerialize();

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
     * @return ShippingApiIntegration
     */
    public function getShippingApiIntegration()
    {
        return $this->shippingApiIntegration;
    }

    /**
     * @param ShippingApiIntegration $shippingApiIntegration
     */
    public function setShippingApiIntegration($shippingApiIntegration)
    {
        $this->shippingApiIntegration = $shippingApiIntegration;
    }

    /**
     * @return Carrier
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @param Carrier $carrier
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
    }

    /**
     * @return ShippingApiService[]
     */
    public function getShippingApiServices()
    {
        return $this->shippingApiServices->toArray();
    }

    /**
     * @param ShippingApiService $shippingApiService
     */
    public function addShippingApiService ($shippingApiService)
    {
        $shippingApiService->setShippingApiCarrier($this);
        $this->shippingApiServices->add($shippingApiService);
    }

}