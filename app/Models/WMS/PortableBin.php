<?php

namespace App\Models\WMS;


class PortableBin extends InventoryLocation implements \JsonSerializable
{

    /**
     * @return string
     */
    public function getObject()
    {
        return 'PortableBin';
    }
}