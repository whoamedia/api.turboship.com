<?php

namespace App\Http\Requests\Orders;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetOrders extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
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
    protected $sourceIds;

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
    protected $statusIds;

    /**
     * @var string|null
     */
    protected $shipmentStatusId;

    /**
     * @var string|null
     */
    protected $externalIds;

    /**
     * @var string|null
     */
    protected $itemSkus;

    /**
     * @var bool|null
     */
    protected $isAddressError;

    /**
     * @var bool|null
     */
    protected $isSkuError;

    /**
     * @var bool|null
     */
    protected $isError;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var string|null
     */
    protected $createdFrom;

    /**
     * @var string|null
     */
    protected $createdTo;

    /**
     * @var string|null
     */
    protected $externalCreatedFrom;

    /**
     * @var string|null
     */
    protected $externalCreatedTo;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $direction;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->itemIds                  = AU::get($data['itemIds']);
        $this->sourceIds                = AU::get($data['sourceIds']);
        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->statusIds                = AU::get($data['statusIds']);
        $this->shipmentStatusId         = AU::get($data['shipmentStatusId']);
        $this->externalIds              = AU::get($data['externalIds']);
        $this->itemSkus                 = AU::get($data['itemSkus']);
        $this->isAddressError           = AU::get($data['isAddressError']);
        $this->isSkuError               = AU::get($data['isSkuError']);
        $this->isError                  = AU::get($data['isError']);
        $this->limit                    = AU::get($data['limit'], 80);
        $this->createdFrom              = AU::get($data['createdFrom']);
        $this->createdTo                = AU::get($data['createdTo']);
        $this->externalCreatedFrom      = AU::get($data['externalCreatedFrom']);
        $this->externalCreatedTo        = AU::get($data['externalCreatedTo']);
        $this->orderBy                  = AU::get($data['orderBy'], 'orders.id');
        $this->direction                = AU::get($data['direction'], 'ASC');
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->itemIds                  = parent::validateIds($this->itemIds, 'itemIds');
        $this->clientIds                = parent::validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->statusIds                = parent::validateIds($this->statusIds, 'statusIds');
        $this->shipmentStatusId         = parent::validateIds($this->shipmentStatusId, 'shipmentStatusId');
        $this->isAddressError           = parent::validateBoolean($this->isAddressError, 'isAddressError');
        $this->isSkuError               = parent::validateBoolean($this->isSkuError, 'isSkuError');
        $this->isError                  = parent::validateBoolean($this->isError, 'isError');
        $this->createdFrom              = parent::validateDate($this->createdFrom, 'createdFrom');
        $this->createdTo                = parent::validateDate($this->createdTo, 'createdTo');
        $this->externalCreatedFrom      = parent::validateDate($this->externalCreatedFrom, 'externalCreatedFrom');
        $this->externalCreatedTo        = parent::validateDate($this->externalCreatedTo, 'externalCreatedTo');

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
        $object['itemIds']              = $this->itemIds;
        $object['sourceIds']            = $this->sourceIds;
        $object['statusIds']            = $this->statusIds;
        $object['shipmentStatusId']     = $this->shipmentStatusId;
        $object['externalIds']          = $this->externalIds;
        $object['itemSkus']             = $this->itemSkus;
        $object['isAddressError']       = $this->isAddressError;
        $object['isSkuError']           = $this->isSkuError;
        $object['isError']              = $this->isError;
        $object['limit']                = $this->limit;
        $object['createdFrom']          = $this->createdFrom;
        $object['createdTo']            = $this->createdTo;
        $object['externalCreatedFrom']  = $this->externalCreatedFrom;
        $object['externalCreatedTo']    = $this->externalCreatedTo;
        $object['orderBy']            = $this->orderBy;
        $object['direction']            = $this->direction;

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
    public function getSourceIds()
    {
        return $this->sourceIds;
    }

    /**
     * @param null|string $sourceIds
     */
    public function setSourceIds($sourceIds)
    {
        $this->sourceIds = $sourceIds;
    }

    /**
     * @return null
     */
    public function getClientIds()
    {
        return $this->clientIds;
    }

    /**
     * @param null $clientIds
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
     * @return null
     */
    public function getStatusIds()
    {
        return $this->statusIds;
    }

    /**
     * @param null $statusIds
     */
    public function setStatusIds($statusIds)
    {
        $this->statusIds = $statusIds;
    }

    /**
     * @return null|string
     */
    public function getShipmentStatusId()
    {
        return $this->shipmentStatusId;
    }

    /**
     * @param null|string $shipmentStatusId
     */
    public function setShipmentStatusId($shipmentStatusId)
    {
        $this->shipmentStatusId = $shipmentStatusId;
    }

    /**
     * @return null
     */
    public function getExternalIds()
    {
        return $this->externalIds;
    }

    /**
     * @param null $externalIds
     */
    public function setExternalIds($externalIds)
    {
        $this->externalIds = $externalIds;
    }

    /**
     * @return null
     */
    public function getItemSkus()
    {
        return $this->itemSkus;
    }

    /**
     * @param null $itemSkus
     */
    public function setItemSkus($itemSkus)
    {
        $this->itemSkus = $itemSkus;
    }

    /**
     * @return bool|null
     */
    public function getIsAddressError()
    {
        return $this->isAddressError;
    }

    /**
     * @param bool|null $isAddressError
     */
    public function setIsAddressError($isAddressError)
    {
        $this->isAddressError = $isAddressError;
    }

    /**
     * @return bool|null
     */
    public function getIsSkuError()
    {
        return $this->isSkuError;
    }

    /**
     * @param bool|null $isSkuError
     */
    public function setIsSkuError($isSkuError)
    {
        $this->isSkuError = $isSkuError;
    }

    /**
     * @return bool|null
     */
    public function getIsError()
    {
        return $this->isError;
    }

    /**
     * @param bool|null $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
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

    /**
     * @return null|string
     */
    public function getCreatedFrom()
    {
        return $this->createdFrom;
    }

    /**
     * @return null|string
     */
    public function getCreatedTo()
    {
        return $this->createdTo;
    }

    /**
     * @return null|string
     */
    public function getReceivedTo()
    {
        return $this->createdTo;
    }

    /**
     * @param null|string $createdTo
     */
    public function setReceivedTo($createdTo)
    {
        $this->createdTo = $createdTo;
    }

    /**
     * @return null|string
     */
    public function getExternalCreatedFrom()
    {
        return $this->externalCreatedFrom;
    }

    /**
     * @param null|string $externalCreatedFrom
     */
    public function setExternalCreatedFrom($externalCreatedFrom)
    {
        $this->externalCreatedFrom = $externalCreatedFrom;
    }

    /**
     * @return null|string
     */
    public function getExternalCreatedTo()
    {
        return $this->externalCreatedTo;
    }

    /**
     * @param null|string $externalCreatedTo
     */
    public function setExternalCreatedTo($externalCreatedTo)
    {
        $this->externalCreatedTo = $externalCreatedTo;
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

}