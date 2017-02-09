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
    protected $rate;

    /**
     * @var float|null
     */
    protected $retailRate;

    /**
     * @var float
     */
    protected $listRate;

    /**
     * @var int
     */
    protected $deliveryDays;

    /**
     * @var bool
     */
    protected $deliveryDateGuaranteed;

    /**
     * @var \DateTime
     */
    protected $deliveryDate;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->shipment                 = AU::get($data['shipment']);
        $this->integratedShippingApi    = AU::get($data['integratedShippingApi']);
        $this->shippingApiService       = AU::get($data['shippingApiService']);
        $this->externalShipmentId       = AU::get($data['externalShipmentId']);
        $this->externalId               = AU::get($data['externalId']);
        $this->rate                     = AU::get($data['rate']);
        $this->retailRate               = AU::get($data['retailRate']);
        $this->listRate                 = AU::get($data['listRate']);
        $this->deliveryDays             = AU::get($data['deliveryDays']);
        $this->deliveryDate             = AU::get($data['deliveryDate']);
        $this->deliveryDateGuaranteed   = AU::get($data['deliveryDateGuaranteed']);
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
        $object['rate']                 = $this->rate;
        $object['retailRate']           = $this->retailRate;
        $object['listRate']             = $this->listRate;
        $object['deliveryDays']         = $this->deliveryDays;
        $object['deliveryDate']         = $this->deliveryDate;
        $object['deliveryDateGuaranteed']= $this->deliveryDateGuaranteed;
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
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
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
     * @return float
     */
    public function getListRate()
    {
        return $this->listRate;
    }

    /**
     * @param float $listRate
     */
    public function setListRate($listRate)
    {
        $this->listRate = $listRate;
    }

    /**
     * @return int
     */
    public function getDeliveryDays()
    {
        return $this->deliveryDays;
    }

    /**
     * @param int $deliveryDays
     */
    public function setDeliveryDays($deliveryDays)
    {
        $this->deliveryDays = $deliveryDays;
    }

    /**
     * @return bool
     */
    public function isDeliveryDateGuaranteed()
    {
        return $this->deliveryDateGuaranteed;
    }

    /**
     * @param bool $deliveryDateGuaranteed
     */
    public function setDeliveryDateGuaranteed($deliveryDateGuaranteed)
    {
        $this->deliveryDateGuaranteed = $deliveryDateGuaranteed;
    }

    /**
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param \DateTime $deliveryDate
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
    }

}