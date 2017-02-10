<?php

namespace App\Models\Shipments;


use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Integrations\ShippingApiService;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Rate implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Shipment
     */
    protected $shipment;

    /**
     * @var IntegratedShippingApi
     */
    protected $integratedShippingApi;

    /**
     * @var ShippingApiService
     */
    protected $shippingApiService;

    /**
     * @var string|null
     */
    protected $externalShipmentId;

    /**
     * @var string|null
     */
    protected $externalId;

    /**
     * @var float
     */
    protected $weight;

    /**
     * @var float
     */
    protected $total;

    /**
     * @var float
     */
    protected $base;

    /**
     * @var float
     */
    protected $tax;

    /**
     * @var float
     */
    protected $fuelSurcharge;

    /**
     * @var float|null
     */
    protected $retailRate;

    /**
     * @var float|null
     */
    protected $listRate;

    /**
     * @var int|null
     */
    protected $deliveryDays;

    /**
     * @var bool|null
     */
    protected $deliveryDateGuaranteed;

    /**
     * @var \DateTime|null
     */
    protected $deliveryDate;

    /**
     * @var bool
     */
    protected $purchased;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $deletedAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->deletedAt                = null;

        $this->shipment                 = AU::get($data['shipment']);
        $this->integratedShippingApi    = AU::get($data['integratedShippingApi']);
        $this->shippingApiService       = AU::get($data['shippingApiService']);
        $this->externalShipmentId       = AU::get($data['externalShipmentId']);
        $this->externalId               = AU::get($data['externalId']);
        $this->total                    = AU::get($data['total']);
        $this->base                     = AU::get($data['base']);
        $this->tax                      = AU::get($data['tax'], 0.00);
        $this->fuelSurcharge            = AU::get($data['fuelSurcharge'], 0.00);
        $this->weight                   = AU::get($data['weight']);
        $this->retailRate               = AU::get($data['retailRate']);
        $this->listRate                 = AU::get($data['listRate']);
        $this->deliveryDays             = AU::get($data['deliveryDays']);
        $this->deliveryDate             = AU::get($data['deliveryDate']);
        $this->deliveryDateGuaranteed   = AU::get($data['deliveryDateGuaranteed']);
        $this->purchased                = AU::get($data['purchased'], false);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        //  $object['integratedShippingApi']= $this->integratedShippingApi->jsonSerialize();
        $object['shippingApiService']   = $this->shippingApiService->jsonSerialize();
        $object['externalShipmentId']   = $this->externalShipmentId;
        $object['externalId']           = $this->externalId;
        $object['weight']               = $this->weight;
        $object['total']                = $this->total;
        $object['base']                 = $this->base;
        $object['tax']                  = $this->tax;
        $object['fuelSurcharge']        = $this->fuelSurcharge;
        $object['retailRate']           = $this->retailRate;
        $object['listRate']             = $this->listRate;
        $object['deliveryDays']         = $this->deliveryDays;
        $object['deliveryDate']         = $this->deliveryDate;
        $object['deliveryDateGuaranteed']= $this->deliveryDateGuaranteed;
        $object['purchased']            = $this->purchased;
        $object['createdAt']            = $this->createdAt;
        $object['deletedAt']            = $this->deletedAt;

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
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return float
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param float $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
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
     * @return float|null
     */
    public function getRetailRate()
    {
        return $this->retailRate;
    }

    /**
     * @param float|null $retailRate
     */
    public function setRetailRate($retailRate)
    {
        $this->retailRate = $retailRate;
    }

    /**
     * @return float|null
     */
    public function getListRate()
    {
        return $this->listRate;
    }

    /**
     * @param float|null $listRate
     */
    public function setListRate($listRate)
    {
        $this->listRate = $listRate;
    }

    /**
     * @return int|null
     */
    public function getDeliveryDays()
    {
        return $this->deliveryDays;
    }

    /**
     * @param int|null $deliveryDays
     */
    public function setDeliveryDays($deliveryDays)
    {
        $this->deliveryDays = $deliveryDays;
    }

    /**
     * @return bool|null
     */
    public function isDeliveryDateGuaranteed()
    {
        return $this->deliveryDateGuaranteed;
    }

    /**
     * @param bool|null $deliveryDateGuaranteed
     */
    public function setDeliveryDateGuaranteed($deliveryDateGuaranteed)
    {
        $this->deliveryDateGuaranteed = $deliveryDateGuaranteed;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param \DateTime|null $deliveryDate
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
    }

    /**
     * @return bool
     */
    public function isPurchased()
    {
        return $this->purchased;
    }

    /**
     * @param bool $purchased
     */
    public function setPurchased($purchased)
    {
        $this->purchased = $purchased;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime|null $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

}