<?php

namespace App\Models\Integrations;


use App\Models\Shipments\Carrier;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShippingIntegrationCarrier implements \JsonSerializable
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
     * @var ShippingIntegration
     */
    protected $shippingIntegration;

    /**
     * @var Carrier
     */
    protected $carrier;

    /**
     * @var ArrayCollection
     */
    protected $shippingIntegrationServices;


    public function __construct($data = [])
    {
        $this->shippingIntegrationServices  = new ArrayCollection();

        $this->name                         = AU::get($data['name']);
        $this->shippingIntegration          = AU::get($data['shippingIntegration']);
        $this->carrier                      = AU::get($data['carrier']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                       = $this->id;
        $object['name']                     = $this->name;
        $object['shippingIntegration']      = $this->shippingIntegration->jsonSerialize();
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
     * @return ShippingIntegration
     */
    public function getShippingIntegration()
    {
        return $this->shippingIntegration;
    }

    /**
     * @param ShippingIntegration $shippingIntegration
     */
    public function setShippingIntegration($shippingIntegration)
    {
        $this->shippingIntegration = $shippingIntegration;
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
     * @return ShippingIntegrationService[]
     */
    public function getShippingIntegrationServices()
    {
        return $this->shippingIntegrationServices->toArray();
    }

    /**
     * @param ShippingIntegrationService $shippingIntegrationService
     */
    public function addShippingIntegrationService ($shippingIntegrationService)
    {
        $shippingIntegrationService->setShippingIntegrationCarrier($this);
        $this->shippingIntegrationServices->add($shippingIntegrationService);
    }

}