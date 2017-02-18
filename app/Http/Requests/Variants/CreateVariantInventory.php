<?php

namespace App\Http\Requests\Variants;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class CreateVariantInventory extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $portableBinId;

    /**
     * @var int
     */
    protected $quantity;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->portableBinId            = AU::get($data['portableBinId']);
        $this->quantity                 = AU::get($data['quantity']);
    }

    public function validate()
    {
        $this->id                       = parent::validateRequiredPositiveInteger($this->id, 'id');
        $this->portableBinId            = parent::validateRequiredPositiveInteger($this->portableBinId, 'portableBinId');
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
        $object['portableBinId']        = $this->portableBinId;
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
    public function getPortableBinId()
    {
        return $this->portableBinId;
    }

    /**
     * @param int $portableBinId
     */
    public function setPortableBinId($portableBinId)
    {
        $this->portableBinId = $portableBinId;
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