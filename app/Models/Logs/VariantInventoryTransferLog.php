<?php

namespace App\Models\Logs;


use App\Models\CMS\Staff;
use App\Models\OMS\Variant;
use App\Models\WMS\InventoryLocation;
use jamesvweston\Utilities\ArrayUtil AS AU;

class VariantInventoryTransferLog implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Variant
     */
    protected $variant;

    /**
     * @var InventoryLocation|null
     */
    protected $fromInventoryLocation;

    /**
     * @var InventoryLocation
     */
    protected $toInventoryLocation;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var Staff
     */
    protected $staff;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->variant                  = AU::get($data['variant']);
        $this->fromInventoryLocation    = AU::get($data['fromInventoryLocation']);
        $this->toInventoryLocation      = AU::get($data['toInventoryLocation']);
        $this->quantity                 = AU::get($data['quantity']);
        $this->staff                    = AU::get($data['staff']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['variant']              = $this->variant->jsonSerialize();
        $object['fromInventoryLocation']= is_null($this->fromInventoryLocation) ? null : $this->fromInventoryLocation->jsonSerialize();
        $object['toInventoryLocation']  = $this->toInventoryLocation->jsonSerialize();
        $object['quantity']             = $this->quantity;
        $object['staff']                = $this->staff->jsonSerialize();
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
     * @return Variant
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @param Variant $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
    }

    /**
     * @return InventoryLocation|null
     */
    public function getFromInventoryLocation()
    {
        return $this->fromInventoryLocation;
    }

    /**
     * @param InventoryLocation|null $fromInventoryLocation
     */
    public function setFromInventoryLocation($fromInventoryLocation)
    {
        $this->fromInventoryLocation = $fromInventoryLocation;
    }

    /**
     * @return InventoryLocation
     */
    public function getToInventoryLocation()
    {
        return $this->toInventoryLocation;
    }

    /**
     * @param InventoryLocation $toInventoryLocation
     */
    public function setToInventoryLocation($toInventoryLocation)
    {
        $this->toInventoryLocation = $toInventoryLocation;
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
     * @return Staff
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * @param Staff $staff
     */
    public function setStaff($staff)
    {
        $this->staff = $staff;
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

}