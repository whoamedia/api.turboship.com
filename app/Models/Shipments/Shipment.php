<?php

namespace App\Models\Shipments;


use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

use App\Models\Locations\Address;

class Shipment implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Address
     */
    protected $fromAddress;

    /**
     * @var Address
     */
    protected $toAddress;

    /**
     * @var Address
     */
    protected $returnAddress;

    /**
     * @var Service
     */
    protected $service;

    /**
     * @var float
     */
    protected $weight;

    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @var Postage|null
     */
    protected $postage;

    /**
     * @var ShippingContainer|null
     */
    protected $shippingContainer;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->items                    = new ArrayCollection();

        $this->fromAddress              = AU::get($data['fromAddress']);
        $this->toAddress                = AU::get($data['toAddress']);
        $this->returnAddress            = AU::get($data['returnAddress']);
        $this->service                  = AU::get($data['service']);
        $this->weight                   = AU::get($data['weight']);
        $this->postage                  = AU::get($data['postage']);
        $this->shippingContainer        = AU::get($data['shippingContainer']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['fromAddress']          = $this->fromAddress->jsonSerialize();
        $object['toAddress']            = $this->toAddress->jsonSerialize();
        $object['returnAddress']        = $this->returnAddress->jsonSerialize();
        $object['service']              = $this->service->jsonSerialize();
        $object['weight']               = $this->weight;
        $object['postage']              = is_null($this->postage) ? null : $this->postage->jsonSerialize();
        $object['shippingContainer']    = is_null($this->shippingContainer) ? null : $this->shippingContainer->jsonSerialize();
        $object['createdAt']            = $this->createdAt;

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
     * @return Address
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param Address $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return Address
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * @param Address $toAddress
     */
    public function setToAddress($toAddress)
    {
        $this->toAddress = $toAddress;
    }

    /**
     * @return Address
     */
    public function getReturnAddress()
    {
        return $this->returnAddress;
    }

    /**
     * @param Address $returnAddress
     */
    public function setReturnAddress($returnAddress)
    {
        $this->returnAddress = $returnAddress;
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

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @param ShipmentItem $item
     */
    public function addItem (ShipmentItem $item)
    {
        $item->setShipment($this);
        $this->items->add($item);
    }

    /**
     * @return ShipmentItem[]
     */
    public function getItems ()
    {
        return $this->items->toArray();
    }

    /**
     * @return Postage|null
     */
    public function getPostage()
    {
        return $this->postage;
    }

    /**
     * @param Postage|null $postage
     */
    public function setPostage($postage)
    {
        $this->postage = $postage;
    }

    /**
     * @return ShippingContainer|null
     */
    public function getShippingContainer()
    {
        return $this->shippingContainer;
    }

    /**
     * @param ShippingContainer|null $shippingContainer
     */
    public function setShippingContainer($shippingContainer)
    {
        $this->shippingContainer = $shippingContainer;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

}