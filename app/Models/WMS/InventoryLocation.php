<?php

namespace App\Models\WMS;


use App\Models\CMS\Organization;
use App\Models\Support\Traits\HasBarcode;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

abstract class InventoryLocation implements \JsonSerializable
{

    use HasBarcode;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $totalQuantity;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * @var ArrayCollection
     */
    protected $inventory;


    public function __construct($data = [])
    {
        $this->inventory                = new ArrayCollection();
        $this->barCode                  = AU::get($data['barCode']);
        $this->totalQuantity            = AU::get($data['totalQuantity'], 0);
        $this->organization             = AU::get($data['organization']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['barCode']              = $this->barCode;
        $object['totalQuantity']        = $this->totalQuantity;

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
     * @return int
     */
    public function getTotalQuantity()
    {
        return $this->totalQuantity;
    }

    /**
     * @param int $totalQuantity
     */
    public function setTotalQuantity($totalQuantity)
    {
        $this->totalQuantity = $totalQuantity;
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
     * @return Inventory[]
     */
    public function getInventory ()
    {
        return $this->inventory->toArray();
    }

    /**
     * @param Inventory $inventory
     */
    public function addInventory ($inventory)
    {
        $inventory->setInventoryLocation($this);
        $this->inventory->add($inventory);
    }

    /**
     * @param   Inventory       $inventory
     * @return  bool
     */
    public function hasInventory($inventory)
    {
        return $this->inventory->contains($inventory);
    }

    /**
     * @param Inventory $inventory
     */
    public function removeInventory ($inventory)
    {
        $this->inventory->removeElement($inventory);
    }

}