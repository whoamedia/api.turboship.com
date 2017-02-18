<?php

namespace App\Services;


use App\Models\CMS\Staff;
use App\Models\Logs\VariantInventoryTransferLog;
use App\Models\OMS\Variant;
use App\Models\Shipments\Shipment;
use App\Models\Support\Validation\ShipmentStatusValidation;
use App\Models\WMS\PortableBin;
use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\Logs\VariantInventoryTransferLogRepository;
use App\Repositories\Doctrine\OMS\VariantRepository;
use EntityManager;

class InventoryService
{

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var VariantInventoryTransferLogRepository
     */
    private $variantInventoryTransferLogRepo;

    public function __construct()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->variantInventoryTransferLogRepo = EntityManager::getRepository('App\Models\Logs\VariantInventoryTransferLog');
    }


    /**
     * @param   Variant         $variant
     * @param   PortableBin     $portableBin
     * @param   int             $quantity
     * @param   Staff           $staff
     * @return  Variant
     */
    public function createVariantInventory (Variant $variant, PortableBin $portableBin, $quantity, Staff $staff)
    {
        for ($i = 0; $i < $quantity; $i++)
        {
            $variantInventory           = new VariantInventory();
            $variantInventory->setInventoryLocation($portableBin);
            $variantInventory->setVariant($variant);
            $variant->addInventory($variantInventory);
        }

        $variant->setTotalQuantity($variant->getTotalQuantity() + $quantity);
        $portableBin->setTotalQuantity($portableBin->getTotalQuantity() + $quantity);

        $variantInventoryTransferLog    = new VariantInventoryTransferLog();
        $variantInventoryTransferLog->setVariant($variant);
        $variantInventoryTransferLog->setFromInventoryLocation(null);
        $variantInventoryTransferLog->setToInventoryLocation($portableBin);
        $variantInventoryTransferLog->setQuantity($quantity);
        $variantInventoryTransferLog->setStaff($staff);
        $this->variantInventoryTransferLogRepo->save($variantInventoryTransferLog);

        return $variant;
    }

    /**
     * @param   Shipment $shipment
     * @return  Shipment
     */
    public function reserveShipmentInventory (Shipment $shipment)
    {
        $shipmentStatusValidation       = new ShipmentStatusValidation();
        if ($shipment->itemsHaveReservedInventory())
        {
            return $shipment;
        }

        foreach ($shipment->getItems() AS $shipmentItem)
        {
            $quantityNeeded             = $shipmentItem->getQuantity() - $shipmentItem->getQuantityReserved();
            if ($quantityNeeded == 0)
                continue;

            $variant                    = $shipmentItem->getOrderItem()->getVariant();

            $availableVariantQuantity   = $variant->getReadyQuantity() - $variant->getReservedQuantity();

            /**
             * The Variant doesn't have any un-reserved inventory
             */
            if ($availableVariantQuantity == 0)
                continue;


            while (true)
            {
                $shipmentItem->setQuantityReserved($shipmentItem->getQuantityReserved() + 1);
                $variant->setReservedQuantity($variant->getReservedQuantity() + 1);

                if ( ($shipmentItem->getQuantity() - $shipmentItem->getQuantityReserved()) == 0)
                    break;
                if ( ($variant->getReadyQuantity() - $variant->getReservedQuantity()) == 0)
                    break;
            }
        }

        if (!$shipment->itemsHaveReservedInventory())
        {
            $status                     = $shipmentStatusValidation->getInsufficientInventory();
            $shipment->setStatus($status);
        }
        else
        {
            $status                     = $shipmentStatusValidation->getPending();
            $shipment->setStatus($status);
        }

        return $shipment;
    }

}