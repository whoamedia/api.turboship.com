<?php

namespace App\Models\WMS;


class Cart extends InventoryLocation implements \JsonSerializable
{

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Cart';
    }

}