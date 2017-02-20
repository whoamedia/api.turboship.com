<?php

namespace App\Listeners;


use App\Jobs\Inventory\ReserveShipmentInventoryJob;
use App\Models\OMS\Order;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\Shipments\CreateShipmentsService;
use App\Utilities\OrderStatusUtility;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Illuminate\Foundation\Bus\DispatchesJobs;
use EntityManager;

class OrderListener
{

    use DispatchesJobs;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * Called after the entity has been saved for the first time
     * @param Order $order
     * @param LifecycleEventArgs $event
     */
    public function postPersistHandler (Order $order, LifecycleEventArgs $event)
    {
        if ($order->getStatus()->getId() == OrderStatusUtility::PENDING_FULFILLMENT_ID)
            $this->handleOrderShipmentLogic($order);
    }

    /**
     * Called after the entity has been updated
     * @param Order $order
     * @param LifecycleEventArgs $event
     */
    public function postUpdateHandler (Order $order, LifecycleEventArgs $event)
    {
        $changeSet                      = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet($event->getEntity());

        //  If the sku has changed search for orders and run them through the approval process
        if (isset($changeSet['status']))
        {
            if ($changeSet['status'][1]->getId() == OrderStatusUtility::PENDING_FULFILLMENT_ID)
                $this->handleOrderShipmentLogic($order);
        }
    }


    private function handleOrderShipmentLogic (Order $order)
    {
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
        $shipmentService                = new CreateShipmentsService($order->getClient(), $order->getClient()->getOptions()->getDefaultShipper());
        $shipments                      = $shipmentService->runOnePerOrder([$order]);

        foreach ($shipments AS $shipment)
        {
            $this->shipmentRepo->saveAndCommit($shipment);

            $job                        = (new ReserveShipmentInventoryJob($shipment->getId()))->onQueue('shipmentInventoryReservation')->delay(config('turboship.variants.readyInventoryDelay'));
            $this->dispatch($job);
        }
    }

}