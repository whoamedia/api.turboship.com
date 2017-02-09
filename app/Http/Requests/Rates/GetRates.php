<?php

namespace App\Http\Requests\Rates;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetRates extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $clientIds;

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $shipmentIds;

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
        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->shipmentIds              = AU::get($data['shipmentIds']);
        $this->shippingApiServiceIds    = AU::get($data['shippingApiServiceIds']);
        $this->serviceIds               = AU::get($data['serviceIds']);
        $this->serviceIds               = AU::get($data['serviceIds']);
        $this->carrierIds               = AU::get($data['carrierIds']);
        $this->orderBy                  = AU::get($data['orderBy'], 'rate.id');
        $this->direction                = AU::get($data['direction'], 'ASC');
        $this->limit                    = AU::get($data['limit'], 80);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->clientIds                = parent::validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->shipmentIds              = parent::validateIds($this->shipmentIds, 'shipmentIds');
        $this->shippingApiServiceIds    = parent::validateIds($this->shippingApiServiceIds, 'shippingApiServiceIds');
        $this->serviceIds               = parent::validateIds($this->serviceIds, 'serviceIds');
        $this->carrierIds               = parent::validateIds($this->carrierIds, 'carrierIds');
        $this->direction                = parent::validateOrderByDirection($this->direction);
    }

    public function clean ()
    {

    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['clientIds']            = $this->clientIds;
        $object['organizationIds']      = $this->organizationIds;
        $object['shipmentIds']          = $this->shipmentIds;
        $object['shippingApiServiceIds']= $this->shippingApiServiceIds;
        $object['serviceIds']           = $this->serviceIds;
        $object['carrierIds']           = $this->carrierIds;
        $object['orderBy']              = $this->orderBy;
        $object['direction']            = $this->direction;
        $object['limit']                = $this->limit;

        return $object;
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
    public function getClientIds()
    {
        return $this->clientIds;
    }

    /**
     * @param null|string $clientIds
     */
    public function setClientIds($clientIds)
    {
        $this->clientIds = $clientIds;
    }

    /**
     * @return null|string
     */
    public function getOrganizationIds()
    {
        return $this->organizationIds;
    }

    /**
     * @param null|string $organizationIds
     */
    public function setOrganizationIds($organizationIds)
    {
        $this->organizationIds = $organizationIds;
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