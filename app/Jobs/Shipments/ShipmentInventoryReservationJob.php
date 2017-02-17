<?php

namespace App\Jobs\Shipments;


use App\Jobs\Job;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\InventoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

/**
 * Should only be called when the Shipment is persisted to the DB for the first time
 * Class ShipmentInventoryReservationJob
 * @package App\Jobs\Shipments
 */
class ShipmentInventoryReservationJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, DispatchesJobs;


    /**
     * @var int
     */
    private $shipmentId;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;


    public function __construct($shipmentId)
    {
        parent::__construct();

        $this->shipmentId               = $shipmentId;
    }

    public function handle ()
    {
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
        $shipment                       = $this->shipmentRepo->getOneById($this->shipmentId);

        if (!$shipment->itemsHaveReservedInventory())
        {
            $inventoryService           = new InventoryService();
            $shipment                   = $inventoryService->reserveShipmentInventory($shipment);
            $this->shipmentRepo->saveAndCommit($shipment);
        }

    }
}