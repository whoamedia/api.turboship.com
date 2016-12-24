<?php

namespace App\Listeners;


use App\Models\CMS\Client;
use App\Models\OMS\Variant;
use App\Services\Order\OrderApprovalService;
use App\Utilities\OrderStatusUtility;
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
        $this->runPendingSkuOrders($variant->getClient(), $variant->getOriginalSku(), $event);
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
            $this->runPendingSkuOrders($variant->getClient(), $changeSet['sku'][1], $event);
    }


    private function runPendingSkuOrders (Client $client, $originalSku, LifecycleEventArgs $event)
    {
        $orderRepo                      = $event->getEntityManager()->getRepository('App\Models\OMS\Order');
        $orderApprovalService           = new OrderApprovalService();

        $orderQuery     = [
            'clientIds'                 => $client->getId(),
            'statusIds'                 => OrderStatusUtility::UNMAPPED_SKU,
            'itemSkus'                  => $originalSku,
        ];

        $result                         = $orderRepo->where($orderQuery);

        foreach ($result AS $order)
        {
            $orderApprovalService->processOrder($order);
            $orderRepo->saveAndCommit($order);
        }
    }
}
