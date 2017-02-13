<?php

namespace App\Models\WMS\Traits;


use App\Models\WMS\Inventory;
use Doctrine\Common\Collections\ArrayCollection;

trait HasInventory
{

    /**
     * @var ArrayCollection
     */
    protected $inventory;

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
        $this->inventory->add($inventory);
    }

    /**
     * @param Inventory $inventory
     */
    public function removeInventory ($inventory)
    {
        $this->inventory->removeElement($inventory);
    }

}