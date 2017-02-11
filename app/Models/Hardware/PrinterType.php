<?php

namespace App\Models\Hardware;


/**
 * @SWG\Definition(@SWG\Xml())
 */
class PrinterType implements \JsonSerializable
{

    /**
     * @SWG\Property(example="92")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="State")
     * @var     string
     */
    protected $name;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
}