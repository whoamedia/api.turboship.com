<?php

namespace App\Jobs\Inventory;


use App\Jobs\Job;
use App\Jobs\Shipments\AutomatedShippingJob;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\Shipments\ShipmentItemRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\InventoryService;
use App\Utilities\ShipmentStatusUtility;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ReadyInventoryAddedJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;


    /**
     * @var int
     */
    private $variantId;

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * @var ShipmentItemRepository
     */
    private $shipmentItemRepo;


    public function __construct($variantId)
    {
        parent::__construct();

        $this->variantId                = $variantId;
    }


    public function handle ()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
        $this->shipmentItemRepo         = EntityManager::getRepository('App\Models\Shipments\ShipmentItem');
        $inventoryService               = new InventoryService();

        $variant                        = $this->variantRepo->getOneById($this->variantId);

        $query          = [
            'statusIds'                 => ShipmentStatusUtility::INSUFFICIENT_INVENTORY,
            'variantIds'                => $this->variantId,
            'organizationIds'           => $variant->getClient()->getOrganization()->getId(),
            'insufficientInventory'     => true
        ];

        $shipmentItemResults            = $this->shipmentItemRepo->where($query);

        foreach ($shipmentItemResults AS $shipmentItem)
        {
            $shipment                   = $shipmentItem->getShipment();
            $inventoryService->reserveShipmentInventory($shipment);
            $this->shipmentRepo->save($shipment);

            if ($shipment->getStatus()->getId() == ShipmentStatusUtility::PENDING)
            {
                //  $job                        = (new AutomatedShippingJob($shipment->getId()))->onQueue('automatedShipping')->delay(30);
                //  $this->dispatch($job);
            }
        }

        $this->shipmentRepo->commit();
    }
}