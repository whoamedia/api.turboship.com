<?php

namespace App\Models\WMS;


use App\Models\OMS\Variant;
use jamesvweston\Utilities\ArrayUtil AS AU;

class VariantInventory extends Inventory implements \JsonSerializable
{

    /**
     * @var Variant
     */
    protected $variant;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->variant                  = AU::get($data['variant']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['variant']              = $this->variant;

        return $object;
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

}