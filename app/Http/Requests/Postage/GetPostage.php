<?php

namespace App\Http\Requests\Postage;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetPostage extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $shipperIds;

    /**
     * @var string|null
     */
    protected $shipmentIds;

    /**
     * @var string|null
     */
    protected $rateIds;

    /**
     * @var string|null
     */
    protected $integratedShippingApiIds;

    /**
     * @var string|null
     */
    protected $shippingApiServiceIds;

    /**
     * @var string|null
     */
    protected $serviceIds;

    /**
     * @var string|null
     */
    protected $carrierIds;

    /**
     * @var bool|null
     */
    protected $isVoided;

    /**
     * @var string|null
     */
    protected $externalIds;

    /**
     * @var string|null
     */
    protected $trackingNumbers;

    /**
     * @var string|null
     */
    protected $createdFrom;

    /**
     * @var string|null
     */
    protected $createdTo;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $direction;

    /**
     * @var int
     */
    protected $limit;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->shipperIds               = AU::get($data['shipperIds']);
        $this->shipmentIds              = AU::get($data['shipmentIds']);
        $this->rateIds                  = AU::get($data['rateIds']);
        $this->integratedShippingApiIds = AU::get($data['integratedShippingApiIds']);
        $this->shippingApiServiceIds    = AU::get($data['shippingApiServiceIds']);
        $this->serviceIds               = AU::get($data['serviceIds']);
        $this->carrierIds               = AU::get($data['carrierIds']);
        $this->externalIds              = AU::get($data['externalIds']);
        $this->trackingNumbers          = AU::get($data['trackingNumbers']);
        $this->isVoided                 = AU::get($data['isVoided']);
        $this->createdFrom              = AU::get($data['createdFrom']);
        $this->createdTo                = AU::get($data['createdTo']);
        $this->orderBy                  = AU::get($data['orderBy'], 'postage.id');
        $this->direction                = AU::get($data['direction'], 'ASC');
        $this->limit                    = AU::get($data['limit'], 80);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->shipperIds               = parent::validateIds($this->shipperIds, 'shipperIds');
        $this->shipmentIds              = parent::validateIds($this->shipmentIds, 'shipmentIds');
        $this->rateIds                  = parent::validateIds($this->rateIds, 'rateIds');
        $this->integratedShippingApiIds = parent::validateIds($this->integratedShippingApiIds, 'integratedShippingApiIds');
        $this->shippingApiServiceIds    = parent::validateIds($this->shippingApiServiceIds, 'shippingApiServiceIds');
        $this->serviceIds               = parent::validateIds($this->serviceIds, 'serviceIds');
        $this->carrierIds               = parent::validateIds($this->carrierIds, 'carrierIds');
        $this->createdFrom              = parent::validateDate($this->createdFrom, 'createdFrom');
        $this->createdTo                = parent::validateDate($this->createdTo, 'createdTo');
        $this->direction                = parent::validateOrderByDirection($this->direction);

        if (!is_null($this->isVoided))
            $this->isVoided             = parent::validateBoolean($this->isVoided, 'isVoided');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['shipperIds']           = $this->shipperIds;
        $object['shipmentIds']          = $this->shipmentIds;
        $object['rateIds']              = $this->rateIds;
        $object['integratedShippingApiIds']= $this->integratedShippingApiIds;
        $object['shippingApiServiceIds']= $this->shippingApiServiceIds;
        $object['serviceIds']           = $this->serviceIds;
        $object['carrierIds']           = $this->carrierIds;
        $object['isVoided']             = $this->isVoided;
        $object['externalIds']          = $this->externalIds;
        $object['trackingNumbers']      = $this->trackingNumbers;
        $object['createdFrom']          = $this->createdFrom;
        $object['createdTo']            = $this->createdTo;
        $object['orderBy']              = $this->orderBy;
        $object['direction']            = $this->direction;
        $object['limit']                = $this->limit;

        return $object;
    }

    public function clean ()
    {

    }

    /**
     * @return null|string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param null|string $ids
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return null|string
     */
    public function getShipperIds()
    {
        return $this->shipperIds;
    }

    /**
     * @param null|string $shipperIds
     */
    public function setShipperIds($shipperIds)
    {
        $this->shipperIds = $shipperIds;
    }

    /**
     * @return null|string
     */
    public function getShipmentIds()
    {
        return $this->shipmentIds;
    }

    /**
     * @param null|string $shipmentIds
     */
    public function setShipmentIds($shipmentIds)
    {
        $this->shipmentIds = $shipmentIds;
    }

    /**
     * @return null|string
     */
    public function getRateIds()
    {
        return $this->rateIds;
    }

    /**
     * @param null|string $rateIds
     */
    public function setRateIds($rateIds)
    {
        $this->rateIds = $rateIds;
    }

    /**
     * @return null|string
     */
    public function getIntegratedShippingApiIds()
    {
        return $this->integratedShippingApiIds;
    }

    /**
     * @param null|string $integratedShippingApiIds
     */
    public function setIntegratedShippingApiIds($integratedShippingApiIds)
    {
        $this->integratedShippingApiIds = $integratedShippingApiIds;
    }

    /**
     * @return null|string
     */
    public function getShippingApiServiceIds()
    {
        return $this->shippingApiServiceIds;
    }

    /**
     * @param null|string $shippingApiServiceIds
     */
    public function setShippingApiServiceIds($shippingApiServiceIds)
    {
        $this->shippingApiServiceIds = $shippingApiServiceIds;
    }

    /**
     * @return null|string
     */
    public function getServiceIds()
    {
        return $this->serviceIds;
    }

    /**
     * @param null|string $serviceIds
     */
    public function setServiceIds($serviceIds)
    {
        $this->serviceIds = $serviceIds;
    }

    /**
     * @return null|string
     */
    public function getCarrierIds()
    {
        return $this->carrierIds;
    }

    /**
     * @param null|string $carrierIds
     */
    public function setCarrierIds($carrierIds)
    {
        $this->carrierIds = $carrierIds;
    }

    /**
     * @return bool|null
     */
    public function getIsVoided()
    {
        return $this->isVoided;
    }

    /**
     * @param bool|null $isVoided
     */
    public function setIsVoided($isVoided)
    {
        $this->isVoided = $isVoided;
    }

    /**
     * @return null|string
     */
    public function getExternalIds()
    {
        return $this->externalIds;
    }

    /**
     * @param null|string $externalIds
     */
    public function setExternalIds($externalIds)
    {
        $this->externalIds = $externalIds;
    }

    /**
     * @return null|string
     */
    public function getTrackingNumbers()
    {
        return $this->trackingNumbers;
    }

    /**
     * @param null|string $trackingNumbers
     */
    public function setTrackingNumbers($trackingNumbers)
    {
        $this->trackingNumbers = $trackingNumbers;
    }

    /**
     * @return null|string
     */
    public function getCreatedFrom()
    {
        return $this->createdFrom;
    }

    /**
     * @param null|string $createdFrom
     */
    public function setCreatedFrom($createdFrom)
    {
        $this->createdFrom = $createdFrom;
    }

    /**
     * @return null|string
     */
    public function getCreatedTo()
    {
        return $this->createdTo;
    }

    /**
     * @param null|string $createdTo
     */
    public function setCreatedTo($createdTo)
    {
        $this->createdTo = $createdTo;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

}