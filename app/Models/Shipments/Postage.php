<?php

namespace App\Models\Shipments;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Postage implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $trackingNumber;

    /**
     * @var string
     */
    protected $labelPath;

    /**
     * @var float
     */
    protected $weight;

    /**
     * @var float
     */
    protected $totalPrice;

    /**
     * @var float
     */
    protected $basePrice;

    /**
     * @var float
     */
    protected $totalTaxes;

    /**
     * @var float
     */
    protected $fuelSurcharge;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var Shipment
     */
    protected $shipment;

    /**
     * @var Service
     */
    protected $service;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->trackingNumber           = AU::get($data['trackingNumber']);
        $this->labelPath                = AU::get($data['labelPath']);
        $this->weight                   = AU::get($data['weight']);
        $this->totalPrice               = AU::get($data['totalPrice']);
        $this->basePrice                = AU::get($data['basePrice']);
        $this->totalTaxes               = AU::get($data['totalTaxes'], 0.00);
        $this->fuelSurcharge            = AU::get($data['fuelSurcharge'], 0.00);
        $this->shipment                 = AU::get($data['shipment']);
        $this->service                  = AU::get($data['service']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['trackingNumber']       = $this->trackingNumber;
        $object['labelPath']            = $this->labelPath;
        $object['weight']               = $this->weight;
        $object['totalPrice']           = $this->totalPrice;
        $object['basePrice']            = $this->basePrice;
        $object['totalTaxes']           = $this->totalTaxes;
        $object['fuelSurcharge']        = $this->fuelSurcharge;
        $object['shipment']             = $this->shipment->jsonSerialize();
        $object['service']              = $this->service->jsonSerialize();
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
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * @param string $trackingNumber
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;
    }

    /**
     * @return string
     */
    public function getLabelPath()
    {
        return $this->labelPath;
    }

    /**
     * @param string $labelPath
     */
    public function setLabelPath($labelPath)
    {
        $this->labelPath = $labelPath;
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
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return float
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * @param float $basePrice
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;
    }

    /**
     * @return float
     */
    public function getTotalTaxes()
    {
        return $this->totalTaxes;
    }

    /**
     * @param float $totalTaxes
     */
    public function setTotalTaxes($totalTaxes)
    {
        $this->totalTaxes = $totalTaxes;
    }

    /**
     * @return float
     */
    public function getFuelSurcharge()
    {
        return $this->fuelSurcharge;
    }

    /**
     * @param float $fuelSurcharge
     */
    public function setFuelSurcharge($fuelSurcharge)
    {
        $this->fuelSurcharge = $fuelSurcharge;
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
     * @return Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param Shipment $shipment
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
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