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

/**
externalProductIds
3807742148,3679023236,1737243076,4907067908,6299149636,6294382340,375163640,4944110212,4979237380,4445097988,3557803524,286186037,521678468,4920905860,4888380356,4765786500,457883760,4758031940,9700689679,349262657,349264753,384740164,384742476,4151921476,519627332,4151823172,384733072,4887741892,1207029124,4458851460,9702911759,9696527375,875093508,4812657284,4754280900,4444907268,9559756751,328375133,521819716,4758011268,4758031748,4758020164,3785580676,1646965764,528827908,1646947140,4458877764,3557750276,4232734532,358115161,328517041,9499109892,4881846788,445200448,384761912,384387944,384329876,384325288,384294176,349506161,349261197,349242245,270285029,270279257,270280237,294105117,543450244,392711816,392397832,392402236,597675268,597878532,597349572,4625856964,892078020,373566040,4444379076,4754320324,9697528271,9697410767,9697469583,270271169,4062697668,270161833,4887507204,9697614607,9699919247,4754293508,303622945,4986145668,311082201,4445128580,4924612292,9697546447,328480501,452615636,4221364228
productIds

 */