<?php

namespace App\Models\WMS;


use jamesvweston\Utilities\ArrayUtil AS AU;

class CartPick extends PickInstruction implements \JsonSerializable
{

    /**
     * @var Cart
     */
    protected $cart;


    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->cart                     = AU::get($data['cart']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['cart']                 = $this->cart->jsonSerialize();

        return $object;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'CartPick';
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

}