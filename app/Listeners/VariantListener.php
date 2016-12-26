<?php

namespace App\Listeners;


use App\Jobs\Orders\OrderSkuMappingJob;
use App\Models\OMS\Variant;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Illuminate\Foundation\Bus\DispatchesJobs;

class VariantListener
{

    use DispatchesJobs;

    /**
     * Called after the entity has been saved for the first time
     * @param Variant $variant
     * @param LifecycleEventArgs $event
     */
    public function postPersistHandler (Variant $variant, LifecycleEventArgs $event)
    {
        $job                            = new OrderSkuMappingJob($variant->getClient()->getId(), $variant->getOriginalSku());
        $this->dispatch($job);
    }

    /**
     * Called after the entity has been updated
     * @param Variant $variant
     * @param LifecycleEventArgs $event
     */
    public function postUpdateHandler (Variant $variant, LifecycleEventArgs $event)
    {
        $changeSet                      = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet($event->getEntity());

        //  If the sku has changed search for orders and run them through the approval process
        if (isset($changeSet['sku']))
            $this->dispatch(new OrderSkuMappingJob($variant->getClient()->getId(), $changeSet['sku'][1]));
    }

}
