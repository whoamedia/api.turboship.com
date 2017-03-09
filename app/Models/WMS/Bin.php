<?php

namespace App\Models\WMS;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Bin extends InventoryLocation implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $aisle;

    /**
     * @var string
     */
    protected $section;

    /**
     * @var string
     */
    protected $row;

    /**
     * @var string
     */
    protected $col;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->aisle                    = AU::get($data['aisle']);
        $this->section                  = AU::get($data['section']);
        $this->row                      = AU::get($data['row']);
        $this->col                      = AU::get($data['col']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['aisle']                = $this->aisle;
        $object['section']              = $this->section;
        $object['row']                  = $this->row;
        $object['col']                  = $this->col;

        return $object;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'Bin';
    }

    /**
     * @return string
     */
    public function getAisle()
    {
        return $this->aisle;
    }

    /**
     * @param string $aisle
     */
    public function setAisle($aisle)
    {
        $this->aisle = $aisle;
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param string $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return string
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * @param string $col
     */
    public function setCol($col)
    {
        $this->col = $col;
    }

}