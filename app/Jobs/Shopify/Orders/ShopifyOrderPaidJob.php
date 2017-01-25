<?php

namespace App\Jobs\Shopify\Orders;


use jamesvweston\Shopify\Models\Responses\ShopifyOrder;
use App\Jobs\Orders\OrderApprovalJob;
use App\Jobs\Shopify\BaseShopifyJob;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Shopify\Mapping\ShopifyOrderMappingService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

/**
 * Follows same logic as ShopifyUpdateOrderJob
 *
 * Class ShopifyOrderPaidJob
 * @package App\Jobs\Shopify\Orders
 */
class ShopifyOrderPaidJob extends BaseShopifyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;


    /**
     * @var ShopifyOrder
     */
    private $shopifyOrder;

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    /**
     * ShopifyImportOrderJob constructor.
     * @param   ShopifyOrder                $shopifyOrder
     * @param   int                         $integratedShoppingCartId
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($shopifyOrder, $integratedShoppingCartId, $shopifyWebHookLogId = null)
    {
        parent::__construct($integratedShoppingCartId, 'orders/paid', $shopifyWebHookLog);
        $this->shopifyOrder             = $shopifyOrder;
    }


    public function handle()
    {
        parent::initialize($this->shopifyOrder->getId());
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $shopifyOrderMappingService     = new ShopifyOrderMappingService($this->integratedShoppingCart->getClient());

        if (!$shopifyOrderMappingService->shouldImportOrder($this->shopifyOrder))
        {
            $this->shopifyWebHookLog->addNote('shouldImportOrder was false');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
            return;
        }

        $order                          = $shopifyOrderMappingService->handleMapping($this->shopifyOrder);
        $entityCreated                  = is_null($order->getId()) ? true : false;
        $this->shopifyWebHookLog->setEntityCreated($entityCreated);

        if (!$order->canUpdate())
        {
            $this->shopifyWebHookLog->addNote('order->canUpdate was false');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
            return;
        }

        $this->orderRepo->saveAndCommit($order);
        $this->shopifyWebHookLog->setEntityId($order->getId());
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);

        $job                        = (new OrderApprovalJob($order->getId()))->onQueue('orderApproval');
        $this->dispatch($job);
    }

}