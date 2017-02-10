<?php

namespace App\Models\Shipments;


use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Integrations\ShippingApiService;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Postage implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Rate
     */
    protected $rate;

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
     * @var IntegratedShippingApi
     */
    protected $integratedShippingApi;

    /**
     * @var ShippingApiService
     */
    protected $shippingApiService;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $voidedAt;

    /**
     * @var Shipment
     */
    protected $shipment;

    /**
     * @var string|null
     */
    protected $externalId;

    /**
     * @var string|null
     */
    protected $externalShipmentId;

    /**
     * @var string|null
     */
    protected $externalRateId;

    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->rate                     = AU::get($data['rate']);
        $this->trackingNumber           = AU::get($data['trackingNumber']);
        $this->labelPath                = AU::get($data['labelPath']);
        $this->weight                   = AU::get($data['weight']);
        $this->totalPrice               = AU::get($data['totalPrice']);
        $this->basePrice                = AU::get($data['basePrice']);
        $this->totalTaxes               = AU::get($data['totalTaxes'], 0.00);
        $this->fuelSurcharge            = AU::get($data['fuelSurcharge'], 0.00);
        $this->shipment                 = AU::get($data['shipment']);
        $this->shippingApiService       = AU::get($data['shippingApiService']);
        $this->integratedShippingApi    = AU::get($data['integratedShippingApi']);
        $this->externalId               = AU::get($data['externalId']);
        $this->externalShipmentId       = AU::get($data['externalShipmentId']);
        $this->externalRateId           = AU::get($data['externalRateId']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['rate']                 = $this->rate->jsonSerialize();
        $object['trackingNumber']       = $this->trackingNumber;
        $object['labelPath']            = $this->labelPath;
        $object['weight']               = $this->weight;
        $object['totalPrice']           = $this->totalPrice;
        $object['basePrice']            = $this->basePrice;
        $object['totalTaxes']           = $this->totalTaxes;
        $object['fuelSurcharge']        = $this->fuelSurcharge;
        $object['externalId']           = $this->externalId;
        $object['externalShipmentId']   = $this->externalShipmentId;
        $object['externalRateId']       = $this->externalRateId;
        $object['shippingApiService']   = $this->shippingApiService->jsonSerialize();
        $object['integratedShippingApi']= $this->integratedShippingApi->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['voidedAt']             = $this->voidedAt;

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
     * @return Rate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param Rate $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
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
     * @return IntegratedShippingApi
     */
    public function getIntegratedShippingApi()
    {
        return $this->integratedShippingApi;
    }

    /**
     * @param IntegratedShippingApi $integratedShippingApi
     */
    public function setIntegratedShippingApi($integratedShippingApi)
    {
        $this->integratedShippingApi = $integratedShippingApi;
    }

    /**
     * @return ShippingApiService
     */
    public function getShippingApiService()
    {
        return $this->shippingApiService;
    }

    /**
     * @param ShippingApiService $shippingApiService
     */
    public function setShippingApiService($shippingApiService)
    {
        $this->shippingApiService = $shippingApiService;
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
    public function getVoidedAt()
    {
        return $this->voidedAt;
    }

    /**
     * @param \DateTime|null $voidedAt
     */
    public function setVoidedAt($voidedAt)
    {
        $this->voidedAt = $voidedAt;
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
     * @return null|string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param null|string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return null|string
     */
    public function getExternalShipmentId()
    {
        return $this->externalShipmentId;
    }

    /**
     * @param null|string $externalShipmentId
     */
    public function setExternalShipmentId($externalShipmentId)
    {
        $this->externalShipmentId = $externalShipmentId;
    }

    /**
     * @return null|string
     */
    public function getExternalRateId()
    {
        return $this->externalRateId;
    }

    /**
     * @param null|string $externalRateId
     */
    public function setExternalRateId($externalRateId)
    {
        $this->externalRateId = $externalRateId;
    }

}