<?php

namespace App\Models\WMS;


use App\Models\CMS\Organization;
use App\Models\Support\Traits\HasBarcode;
use App\Models\WMS\Traits\HasInventory;
use jamesvweston\Utilities\ArrayUtil AS AU;

abstract class InventoryLocation implements \JsonSerializable
{

    use HasInventory, HasBarcode;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Organization
     */
    protected $organization;


    public function __construct($data = [])
    {
        $this->barCode                  = AU::get($data['barCode']);
        $this->organization             = AU::get($data['organization']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['barCode']              = $this->barCode;

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

}