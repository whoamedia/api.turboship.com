<?php

namespace App\Listeners;


use App\Jobs\Orders\OrderApprovalJob;
use App\Models\OMS\Variant;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderApprovalService;
use App\Utilities\OrderStatusUtility;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Illuminate\Foundation\Bus\DispatchesJobs;
use EntityManager;

class VariantListener
{

    use DispatchesJobs;


    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * Called after the entity has been saved for the first time
     * @param Variant $variant
     * @param LifecycleEventArgs $event
     */
    public function postPersistHandler (Variant $variant, LifecycleEventArgs $event)
    {
        $this->findOrders($variant->getClient()->getId(), $variant->getOriginalSku());
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
        {
            $this->findOrders($variant->getClient()->getId(), $changeSet['sku'][1]);
        }
    }


    private function findOrders ($clientId, $sku)
    {
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $orderApprovalService           = new OrderApprovalService();

        $orderQuery     = [
            'clientIds'                 => $clientId,
            'statusIds'                 => OrderStatusUtility::UNMAPPED_SKU,
            'itemSkus'                  => $sku,
        ];

        $result                         = $this->orderRepo->where($orderQuery);
        foreach ($result AS $order)
        {
            $order                          = $orderApprovalService->processOrder($order);
            $this->orderRepo->saveAndCommit($order);
        }
    }

}
