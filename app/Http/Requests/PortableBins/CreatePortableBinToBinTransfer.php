<?php

namespace App\Http\Requests\PortableBins;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class CreatePortableBinToBinTransfer extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $variantId;

    /**
     * @var int
     */
    protected $binId;

    /**
     * @var int
     */
    protected $quantity;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->variantId                = AU::get($data['variantId']);
        $this->binId                    = AU::get($data['binId']);
        $this->quantity                 = AU::get($data['quantity']);
    }

    public function validate()
    {
        $this->id                       = parent::validateRequiredPositiveInteger($this->id, 'id');
        $this->binId                    = parent::validateRequiredPositiveInteger($this->binId, 'binId');
        $this->variantId                = parent::validateRequiredPositiveInteger($this->variantId, 'variantId');
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
        $object['binId']                = $this->binId;
        $object['variantId']            = $this->variantId;
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
    public function getVariantId()
    {
        return $this->variantId;
    }

    /**
     * @param int $variantId
     */
    public function setVariantId($variantId)
    {
        $this->variantId = $variantId;
    }

    /**
     * @return int
     */
    public function getBinId()
    {
        return $this->binId;
    }

    /**
     * @param int $binId
     */
    public function setBinId($binId)
    {
        $this->binId = $binId;
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