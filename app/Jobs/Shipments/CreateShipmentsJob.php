<?php

namespace App\Jobs\Shipments;


use App\Jobs\Inventory\ReserveShipmentInventoryJob;
use App\Jobs\Job;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Repositories\Doctrine\Shipments\ShipperRepository;
use App\Services\Shipments\CreateShipmentsService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class CreateShipmentsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, DispatchesJobs;


    /**
     * @var int
     */
    private $orderId;

    /**
     * @var int
     */
    private $shipperId;

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var ShipperRepository
     */
    private $shipperRepo;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * CreateShipmentsJob constructor.
     * @param   int     $orderId
     * @param   int     $shipperId
     */
    public function __construct($orderId, $shipperId)
    {
        parent::__construct();

        $this->orderId                  = $orderId;
        $this->shipperId                = $shipperId;
    }


    public function handle()
    {
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->shipperRepo              = EntityManager::getRepository('App\Models\Shipments\Shipper');
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');

        $order                          = $this->orderRepo->getOneById($this->orderId);
        $shipper                        = $this->shipperRepo->getOneById($this->shipperId);

        $client                         = $order->getClient();

        $shipmentService                = new CreateShipmentsService($client, $shipper);
        $shipments                      = $shipmentService->runOnePerOrder([$order]);

        foreach ($shipments AS $shipment)
        {
            $this->shipmentRepo->saveAndCommit($shipment);

            $job                            = (new ReserveShipmentInventoryJob($shipment->getId()))->onQueue('shipmentInventoryReservation')->delay(config('turboship.variants.readyInventoryDelay'));
            $this->dispatch($job);
        }
    }

}