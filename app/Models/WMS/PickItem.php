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
     * @var Tote
     */
    protected $tote;

    /**
     * @var int
     */
    protected $quantityRequired;

    /**
     * @var int
     */
    protected $quantityPicked;

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
        $this->tote                     = AU::get($data['tote']);
        $this->quantityRequired         = AU::get($data['quantityRequired']);
        $this->quantityPicked           = AU::get($data['quantityPicked'], 0);
        $this->completedAt              = AU::get($data['completedAt']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['variant']              = $this->variant->jsonSerialize();
        $object['tote']                 = $this->tote->jsonSerialize();
        $object['quantityRequired']     = $this->quantityRequired;
        $object['quantityPicked']       = $this->quantityPicked;
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
    public function getQuantityRequired()
    {
        return $this->quantityRequired;
    }

    /**
     * @param int $quantityRequired
     */
    public function setQuantityRequired($quantityRequired)
    {
        $this->quantityRequired = $quantityRequired;
    }

    /**
     * @return int
     */
    public function getQuantityPicked()
    {
        return $this->quantityPicked;
    }

    /**
     * @param int $quantityPicked
     */
    public function setQuantityPicked($quantityPicked)
    {
        $this->quantityPicked = $quantityPicked;
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