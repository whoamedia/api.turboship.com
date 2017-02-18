<?php

namespace App\Jobs\Inventory;


use App\Jobs\Job;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\InventoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ReserveShipmentInventoryJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


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

        $inventoryService               = new InventoryService();
        $inventoryService->reserveShipmentInventory($shipment);
        $this->shipmentRepo->saveAndCommit($shipment);
    }
}