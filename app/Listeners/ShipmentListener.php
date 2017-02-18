<?php

namespace App\Listeners;


use App\Jobs\Shipments\AutomatedShippingJob;
use App\Models\Shipments\Shipment;
use App\Utilities\ShipmentStatusUtility;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ShipmentListener
{

    use DispatchesJobs;

    /**
     * Called after the entity has been updated
     * @param Shipment $shipment
     * @param LifecycleEventArgs $event
     */
    public function postUpdateHandler (Shipment $shipment, LifecycleEventArgs $event)
    {
        if (isset($changeSet['status']))
        {
            if ($shipment->getStatus()->getId() == ShipmentStatusUtility::PENDING)
                $this->testShip($shipment);
        }
    }


    private function testShip (Shipment $shipment)
    {
        $job                            = (new AutomatedShippingJob($shipment->getId()))->onQueue('automatedShipping');
        $this->dispatch($job);
    }

}