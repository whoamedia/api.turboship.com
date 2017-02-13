<?php

namespace App\Models\WMS;


use App\Models\CMS\Organization;
use jamesvweston\Utilities\ArrayUtil AS AU;

abstract class Inventory implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $barCode;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var InventoryLocation
     */
    protected $inventoryLocation;


    public function __construct($data = [])
    {
        $this->barCode                  = AU::get($data['barCode']);
        $this->organization             = AU::get($data['organization']);
        $this->inventoryLocation        = AU::get($data['inventoryLocation']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['barCode']              = $this->barCode;
        $object['inventoryLocation']    = $this->inventoryLocation;

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
     * @return string
     */
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * @param string $barCode
     */
    public function setBarCode($barCode)
    {
        $this->barCode = $barCode;
    }

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return InventoryLocation
     */
    public function getInventoryLocation()
    {
        return $this->inventoryLocation;
    }

    /**
     * @param InventoryLocation $inventoryLocation
     */
    public function setInventoryLocation($inventoryLocation)
    {
        $this->inventoryLocation = $inventoryLocation;
    }

}