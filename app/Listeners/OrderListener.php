<?php

namespace App\Listeners;


use App\Jobs\Shipments\CreateShipmentsJob;
use App\Models\OMS\Order;
use App\Utilities\OrderStatusUtility;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Illuminate\Foundation\Bus\DispatchesJobs;

class OrderListener
{

    use DispatchesJobs;

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
        $job                            = (new CreateShipmentsJob($order->getId(), 1))->onQueue('orderShipments');
        $this->dispatch($job);
    }

}