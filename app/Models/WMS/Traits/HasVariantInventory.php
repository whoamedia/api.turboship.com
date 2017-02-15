<?php

namespace App\Models\WMS\Traits;


use App\Models\WMS\VariantInventory;
use Doctrine\Common\Collections\ArrayCollection;

trait HasVariantInventory
{

    /**
     * @var ArrayCollection
     */
    protected $inventory;

    /**
     * @return VariantInventory[]
     */
    public function getInventory ()
    {
        return $this->inventory->toArray();
    }

    /**
     * @param VariantInventory $inventory
     */
    public function addInventory ($inventory)
    {
        $this->inventory->add($inventory);
    }

    /**
     * @param VariantInventory $inventory
     */
    public function removeInventory ($inventory)
    {
        $this->inventory->removeElement($inventory);
    }

    public function emptyInventory ()
    {
        $this->inventory->clear();
    }

}