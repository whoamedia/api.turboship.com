<?php

namespace App\Models\OMS;


use App\Models\CMS\User;
use jamesvweston\Utilities\ArrayUtil AS AU;

class OrderStatusHistory implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderStatus
     */
    protected $status;

    /**
     * @var User|null
     */
    protected $createdBy;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * OrderStatusHistory constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->order                    = AU::get($data['order']);
        $this->status                   = AU::get($data['status']);
        $this->createdBy                = \Auth::getUser();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['status']               = $this->status->jsonSerialize();
        $object['createdBy']            = is_null($this->createdBy) ? null : $this->createdBy->jsonSerialize();
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
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return OrderStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param OrderStatus $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return User|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User|null $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}