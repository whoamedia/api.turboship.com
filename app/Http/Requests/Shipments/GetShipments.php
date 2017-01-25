<?php

namespace App\Http\Requests\Shipments;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetShipments extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $itemIds;

    /**
     * @var string|null
     */
    protected $orderIds;

    /**
     * @var string|null
     */
    protected $orderItemIds;

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
    protected $serviceIds;

    /**
     * @var string|null
     */
    protected $carrierIds;

    /**
     * @var string|null
     */
    protected $shippingContainerIds;

    /**
     * @var string|null
     */
    protected $toAddressCountryIds;

    /**
     * @var string|null
     */
    protected $statusIds;

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
        $this->itemIds                  = AU::get($data['itemIds']);
        $this->orderIds                 = AU::get($data['orderIds']);
        $this->orderItemIds             = AU::get($data['orderItemIds']);
        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->serviceIds               = AU::get($data['serviceIds']);
        $this->carrierIds               = AU::get($data['carrierIds']);
        $this->shippingContainerIds     = AU::get($data['shippingContainerIds']);
        $this->toAddressCountryIds      = AU::get($data['toAddressCountryIds']);
        $this->statusIds                = AU::get($data['statusIds']);
        $this->trackingNumbers          = AU::get($data['trackingNumbers']);
        $this->createdFrom              = AU::get($data['createdFrom']);
        $this->createdTo                = AU::get($data['createdTo']);
        $this->orderBy                  = AU::get($data['orderBy'], 'shipment.id');
        $this->direction                = AU::get($data['direction'], 'ASC');
        $this->limit                    = AU::get($data['limit'], 80);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->itemIds                  = parent::validateIds($this->itemIds, 'itemIds');
        $this->orderIds                 = parent::validateIds($this->orderIds, 'orderIds');
        $this->orderItemIds             = parent::validateIds($this->orderItemIds, 'orderItemIds');
        $this->clientIds                = parent::validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->serviceIds               = parent::validateIds($this->serviceIds, 'serviceIds');
        $this->carrierIds               = parent::validateIds($this->carrierIds, 'carrierIds');
        $this->shippingContainerIds     = parent::validateIds($this->shippingContainerIds, 'shippingContainerIds');
        $this->toAddressCountryIds      = parent::validateIds($this->toAddressCountryIds, 'toAddressCountryIds');
        $this->statusIds                = parent::validateIds($this->statusIds, 'statusIds');
        $this->createdFrom              = parent::validateDate($this->createdFrom, 'createdFrom');
        $this->createdTo                = parent::validateDate($this->createdTo, 'createdTo');
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
        $object['itemIds']              = $this->itemIds;
        $object['orderIds']             = $this->orderIds;
        $object['orderItemIds']         = $this->orderItemIds;
        $object['clientIds']            = $this->clientIds;
        $object['organizationIds']      = $this->organizationIds;
        $object['serviceIds']           = $this->serviceIds;
        $object['carrierIds']           = $this->carrierIds;
        $object['shippingContainerIds'] = $this->shippingContainerIds;
        $object['toAddressCountryIds']  = $this->toAddressCountryIds;
        $object['statusIds']            = $this->statusIds;
        $object['trackingNumbers']      = $this->trackingNumbers;
        $object['createdFrom']          = $this->createdFrom;
        $object['createdTo']            = $this->createdTo;
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
    public function getItemIds()
    {
        return $this->itemIds;
    }

    /**
     * @param null|string $itemIds
     */
    public function setItemIds($itemIds)
    {
        $this->itemIds = $itemIds;
    }

    /**
     * @return null|string
     */
    public function getOrderIds()
    {
        return $this->orderIds;
    }

    /**
     * @param null|string $orderIds
     */
    public function setOrderIds($orderIds)
    {
        $this->orderIds = $orderIds;
    }

    /**
     * @return null|string
     */
    public function getOrderItemIds()
    {
        return $this->orderItemIds;
    }

    /**
     * @param null|string $orderItemIds
     */
    public function setOrderItemIds($orderItemIds)
    {
        $this->orderItemIds = $orderItemIds;
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
     * @return null|string
     */
    public function getShippingContainerIds()
    {
        return $this->shippingContainerIds;
    }

    /**
     * @param null|string $shippingContainerIds
     */
    public function setShippingContainerIds($shippingContainerIds)
    {
        $this->shippingContainerIds = $shippingContainerIds;
    }

    /**
     * @return null|string
     */
    public function getToAddressCountryIds()
    {
        return $this->toAddressCountryIds;
    }

    /**
     * @param null|string $toAddressCountryIds
     */
    public function setToAddressCountryIds($toAddressCountryIds)
    {
        $this->toAddressCountryIds = $toAddressCountryIds;
    }

    /**
     * @return null|string
     */
    public function getStatusIds()
    {
        return $this->statusIds;
    }

    /**
     * @param null|string $statusIds
     */
    public function setStatusIds($statusIds)
    {
        $this->statusIds = $statusIds;
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