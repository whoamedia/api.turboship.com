<?php

namespace App\Models\Support;

use jamesvweston\Utilities\ArrayUtil AS AU;

class Dimension implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var float
     */
    protected $length;

    /**
     * @var float
     */
    protected $width;

    /**
     * @var float
     */
    protected $height;


    public function __construct($data = [])
    {
        $this->length                   = AU::get($data['length']);
        $this->width                    = AU::get($data['width']);
        $this->height                   = AU::get($data['height']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['length']               = $this->length;
        $object['width']                = $this->width;
        $object['height']               = $this->height;

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
     * @return float
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param float $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

}