<?php

namespace App\Models\OMS;


use App\Utilities\OrderStatusUtility;
use jamesvweston\Utilities\ArrayUtil AS AU;

class OrderItem implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $externalId;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $declaredValue;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderStatus
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * OrderItem constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->externalId               = AU::get($data['externalId']);
        $this->sku                      = AU::get($data['sku']);
        $this->quantity                 = AU::get($data['quantity']);
        $this->declaredValue            = AU::get($data['declaredValue']);
        $this->status                   = AU::get($data['status']);
        $this->order                    = AU::get($data['order']);

        if (is_null($this->status))
        {
            $orderStatusUtility         = new OrderStatusUtility();
            $this->setStatus($orderStatusUtility->getCreated());
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['externalId']           = $this->externalId;
        $object['sku']                  = $this->sku;
        $object['quantity']             = $this->quantity;
        $object['declaredValue']        = $this->declaredValue;
        $object['status']               = $this->status->jsonSerialize();

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
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getDeclaredValue()
    {
        return $this->declaredValue;
    }

    /**
     * @param float $declaredValue
     */
    public function setDeclaredValue($declaredValue)
    {
        $this->declaredValue = $declaredValue;
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}