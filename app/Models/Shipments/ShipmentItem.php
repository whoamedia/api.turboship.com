<?php

namespace App\Models\Shipments;


use App\Models\OMS\OrderItem;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShipmentItem implements \JsonSerializable
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
     * @var OrderItem
     */
    protected $orderItem;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var int
     */
    protected $quantityReserved;


    public function __construct($data = [])
    {
        $this->shipment                 = AU::get($data['shipment']);
        $this->orderItem                = AU::get($data['orderItem']);
        $this->quantity                 = AU::get($data['quantity']);
        $this->quantityReserved         = AU::get($data['quantityReserved'], 0);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['orderItem']            = $this->orderItem->jsonSerialize();
        $object['quantity']             = $this->quantity;
        $object['quantityReserved']     = $this->quantityReserved;

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
     * @return OrderItem
     */
    public function getOrderItem()
    {
        return $this->orderItem;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function setOrderItem($orderItem)
    {
        $this->orderItem = $orderItem;
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
     * @return int
     */
    public function getQuantityReserved()
    {
        return $this->quantityReserved;
    }

    /**
     * @param int $quantityReserved
     */
    public function setQuantityReserved($quantityReserved)
    {
        $this->quantityReserved = $quantityReserved;
    }

}