<?php

namespace App\Models\WMS;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Tote extends InventoryLocation implements \JsonSerializable
{

    /**
     * @var float
     */
    protected $weight;


    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->weight                   = AU::get($data['weight']);
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

}