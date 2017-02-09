<?php

namespace App\Models\Shipments;


use App\Models\CMS\Client;
use App\Models\Support\Dimension;
use App\Models\Support\Image;
use App\Models\Support\ShipmentStatus;
use App\Models\Support\Validation\ShipmentStatusValidation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use jamesvweston\Utilities\ArrayUtil AS AU;

use App\Models\Locations\Address;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Shipment implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Shipper
     */
    protected $shipper;

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
     * @var Service|null
     */
    protected $service;

    /**
     * @var float|null
     */
    protected $weight;

    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @var ArrayCollection
     */
    protected $rates;

    /**
     * @var ArrayCollection
     */
    protected $images;

    /**
     * @var Postage|null
     */
    protected $postage;

    /**
     * @var ShippingContainer|null
     */
    protected $shippingContainer;

    /**
     * @var Dimension|null
     */
    protected $dimensions;

    /**
     * @var ShipmentStatus
     */
    protected $status;

    /**
     * @var \DateTime|null
     */
    protected $shippedAt;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->items                    = new ArrayCollection();
        $this->rates                    = new ArrayCollection();
        $this->images                   = new ArrayCollection();

        $this->shipper                  = AU::get($data['shipper']);
        $this->fromAddress              = AU::get($data['fromAddress']);
        $this->toAddress                = AU::get($data['toAddress']);
        $this->returnAddress            = AU::get($data['returnAddress']);
        $this->service                  = AU::get($data['service']);
        $this->weight                   = AU::get($data['weight']);
        $this->postage                  = AU::get($data['postage']);
        $this->shippingContainer        = AU::get($data['shippingContainer']);
        $this->dimensions               = AU::get($data['dimensions']);
        $this->status                   = AU::get($data['status']);
        $this->shippedAt                = AU::get($data['shippedAt']);

        if (is_null($this->status))
        {
            $shipmentStatusValidation   = new ShipmentStatusValidation();
            $this->status               = $shipmentStatusValidation->getPending();
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['shipper']              = $this->shipper->jsonSerialize();
        $object['fromAddress']          = $this->fromAddress->jsonSerialize();
        $object['toAddress']            = $this->toAddress->jsonSerialize();
        $object['returnAddress']        = $this->returnAddress->jsonSerialize();
        $object['service']              = !is_null($this->service) ? $this->service->jsonSerialize() : null;
        $object['weight']               = $this->weight;
        $object['shippingContainer']    = is_null($this->shippingContainer) ? null : $this->shippingContainer->jsonSerialize();
        $object['dimensions']           = is_null($this->dimensions) ? null : $this->dimensions->jsonSerialize();
        $object['status']               = $this->status->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['shippedAt']            = $this->shippedAt;

        $object['items']                = [];
        foreach ($this->getItems() AS $shipmentItem)
            $object['items'][]          = $shipmentItem->jsonSerialize();

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
     * @return Shipper
     */
    public function getShipper()
    {
        return $this->shipper;
    }

    /**
     * @param Shipper $shipper
     */
    public function setShipper($shipper)
    {
        $this->shipper = $shipper;
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
     * @return Service|null
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service|null $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
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

        if (is_null($this->shippingContainer))
            $this->setDimensions(null);
        else
        {
            $dimensions             = new Dimension($this->shippingContainer->jsonSerialize());
            $this->setDimensions($dimensions);
        }
    }

    /**
     * @return Dimension|null
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * @param Dimension|null $dimensions
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;
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

    /**
     * @return \DateTime|null
     */
    public function getShippedAt()
    {
        return $this->shippedAt;
    }

    /**
     * @param \DateTime|null $shippedAt
     */
    public function setShippedAt($shippedAt)
    {
        $this->shippedAt = $shippedAt;
    }

    /**
     * Can we safely ship this shipment?
     * @return bool
     */
    public function canShip ()
    {
        return true;
    }

    /**
     * @return  Client
     */
    public function getClient ()
    {
        foreach ($this->getItems() AS $shipmentItem)
            return $shipmentItem->getOrderItem()->getOrder()->getClient();
    }

    /**
     * @return Rate[]
     */
    public function getRates ()
    {
        return $this->rates->toArray();
    }

    /**
     * @param Rate $rate
     */
    public function addRate (Rate $rate)
    {
        $rate->setShipment($this);
        $this->rates->add($rate);
    }


    public function clearRates ()
    {
        $this->rates->clear();
    }

    /**
     * @param   Rate $rate
     * @return  bool
     */
    public function hasRate (Rate $rate)
    {
        return $this->rates->contains($rate);
    }

    /**
     * Can we safely rate this Shipment?
     * @throws  BadRequestHttpException
     * @return  bool
     */
    public function canRate ()
    {
        if (is_null($this->getShippingContainer()))
            throw new BadRequestHttpException('Shipment needs a ShippingContainer');
        else if (is_null($this->getWeight()) || $this->getWeight() <= 0)
            throw new BadRequestHttpException('Shipment needs a weight');
        else if (!is_null($this->getPostage()))
            throw new BadRequestHttpException('Shipment already has postage');
        else
            return true;
    }

    /**
     * Can we safely purchase postage for this Shipment?
     * @param   Rate $rate
     * @throws  BadRequestHttpException
     * @return  bool
     */
    public function canPurchasePostage (Rate $rate)
    {
        if (!$this->hasRate($rate))
            throw new BadRequestHttpException('Shipment does not have provided Rate');
        else if (!is_null($this->getPostage()))
            throw new BadRequestHttpException('Shipment already has postage');
        else
            return true;
    }

    /**
     * Can we safely void the postage for this Shipment?
     * @throws  BadRequestHttpException
     * @return  bool
     */
    public function canVoidPostage ()
    {
        if (is_null($this->getPostage()))
            throw new BadRequestHttpException('Shipment does not have postage to void');
        else
            return true;
    }

    /**
     * @return Image[]
     */
    public function getImages ()
    {
        return $this->images->toArray();
    }

    /**
     * @param Image $image
     */
    public function addImage (Image $image)
    {
        $this->images->add($image);
    }

    /**
     * @param   Image $image
     * @return  bool
     */
    public function hasImage (Image $image)
    {
        return $this->images->contains($image);
    }

    /**
     * @param   Image $image
     */
    public function removeImage (Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * @return ShipmentStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param ShipmentStatus $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}