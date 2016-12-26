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
    protected $createdFrom;

    /**
     * @var string|null
     */
    protected $createdTo;

    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->itemIds                  = AU::get($data['itemIds']);
        $this->orderIds                 = AU::get($data['orderIds']);
        $this->orderItemIds             = AU::get($data['orderItemIds']);
        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->createdFrom              = AU::get($data['createdFrom']);
        $this->createdTo                = AU::get($data['createdTo']);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->itemIds                  = parent::validateIds($this->itemIds, 'itemIds');
        $this->orderIds                 = parent::validateIds($this->orderIds, 'orderIds');
        $this->orderItemIds             = parent::validateIds($this->orderItemIds, 'orderItemIds');
        $this->clientIds                = parent::validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->createdFrom              = parent::validateDate($this->createdFrom, 'createdFrom');
        $this->createdTo                = parent::validateDate($this->createdTo, 'createdTo');
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
        $object['createdFrom']          = $this->createdFrom;
        $object['createdTo']            = $this->createdTo;

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

}