<?php

namespace App\Http\Requests\Variants;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class TransferVariantInventory extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $fromInventoryLocationId;

    /**
     * @var int
     */
    protected $toInventoryLocationId;

    /**
     * @var int
     */
    protected $quantity;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->fromInventoryLocationId  = AU::get($data['fromInventoryLocationId']);
        $this->toInventoryLocationId    = AU::get($data['toInventoryLocationId']);
        $this->quantity                 = AU::get($data['quantity']);
    }

    public function validate()
    {
        $this->id                       = parent::validateRequiredPositiveInteger($this->id, 'id');
        $this->toInventoryLocationId    = parent::validateRequiredPositiveInteger($this->toInventoryLocationId, 'toInventoryLocationId');
        $this->fromInventoryLocationId  = parent::validateRequiredPositiveInteger($this->fromInventoryLocationId, 'fromInventoryLocationId');
        $this->quantity                 = parent::validateRequiredPositiveInteger($this->quantity, 'quantity');
    }

    public function clean()
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['toInventoryLocationId']= $this->toInventoryLocationId;
        $object['fromInventoryLocationId'] = $this->fromInventoryLocationId;
        $object['quantity']             = $this->quantity;

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
     * @return int
     */
    public function getFromInventoryLocationId()
    {
        return $this->fromInventoryLocationId;
    }

    /**
     * @param int $fromInventoryLocationId
     */
    public function setFromInventoryLocationId($fromInventoryLocationId)
    {
        $this->fromInventoryLocationId = $fromInventoryLocationId;
    }

    /**
     * @return int
     */
    public function getToInventoryLocationId()
    {
        return $this->toInventoryLocationId;
    }

    /**
     * @param int $toInventoryLocationId
     */
    public function setToInventoryLocationId($toInventoryLocationId)
    {
        $this->toInventoryLocationId = $toInventoryLocationId;
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

}