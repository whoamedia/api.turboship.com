<?php

namespace App\Models\WMS;


use App\Models\OMS\Variant;
use jamesvweston\Utilities\ArrayUtil AS AU;

class PickItem implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var PickLocation
     */
    protected $pickLocation;

    /**
     * @var Variant
     */
    protected $variant;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var Tote
     */
    protected $tote;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $completedAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->pickLocation             = AU::get($data['pickLocation']);
        $this->variant                  = AU::get($data['variant']);
        $this->quantity                 = AU::get($data['quantity']);
        $this->tote                     = AU::get($data['tote']);
        $this->completedAt              = AU::get($data['completedAt']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['bin']                  = $this->bin->jsonSerialize();
        $object['variant']              = $this->variant->jsonSerialize();
        $object['quantity']             = $this->quantity;
        $object['tote']                 = $this->tote->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['completedAt']          = is_null($this->completedAt) ? null : $this->completedAt;

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
     * @return PickLocation
     */
    public function getPickLocation()
    {
        return $this->pickLocation;
    }

    /**
     * @param PickLocation $pickLocation
     */
    public function setPickLocation($pickLocation)
    {
        $this->pickLocation = $pickLocation;
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
     * @return Tote
     */
    public function getTote()
    {
        return $this->tote;
    }

    /**
     * @param Tote $tote
     */
    public function setTote($tote)
    {
        $this->tote = $tote;
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
     * @return \DateTime|null
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * @param \DateTime|null $completedAt
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
    }

}