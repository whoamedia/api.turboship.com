<?php

namespace App\Services;


use App\Models\OMS\Variant;
use App\Models\WMS\Bin;
use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\OMS\VariantRepository;
use EntityManager;

class InventoryService
{

    /**
     * @var VariantRepository
     */
    private $variantRepo;


    public function __construct()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
    }


    /**
     * @param   Variant     $variant
     * @param   Bin         $bin
     * @return  Variant
     */
    public function createVariantInventory (Variant $variant, Bin $bin)
    {
        $variantInventory               = new VariantInventory();
        $variantInventory->setInventoryLocation($bin);
        $variantInventory->setVariant($variant);
        $variant->addInventory($variantInventory);

        $variant->setTotalQuantity($variant->getTotalQuantity() + 1);
        $variant->setReadyQuantity($variant->getReadyQuantity() + 1);
        $bin->setTotalQuantity($bin->getTotalQuantity() + 1);

        return $variant;
    }

}