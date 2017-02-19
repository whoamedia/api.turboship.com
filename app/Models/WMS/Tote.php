<?php

namespace App\Models\WMS;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Tote extends InventoryLocation implements \JsonSerializable
{

    /**
     * @var float
     */
    protected $weight;

    /**
     * @var Cart|null
     */
    protected $cart;


    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->weight                   = AU::get($data['weight']);
        $this->cart                     = AU::get($data['cart']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['weight']               = $this->weight;

        return $object;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Tote';
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return Cart|null
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Cart|null $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

}