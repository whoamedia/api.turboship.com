<?php

namespace App\Jobs\Shopify\Orders;


use App\Services\Order\OrderApprovalService;
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

class ShopifyUpdateOrderJob extends BaseShopifyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;


    /**
     * @var string
     */
    private $jsonShopifyOrder;

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    /**
     * ShopifyImportOrderJob constructor.
     * @param   string                      $jsonShopifyOrder
     * @param   int                         $integratedShoppingCartId
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($jsonShopifyOrder, $integratedShoppingCartId, $shopifyWebHookLogId = null)
    {
        parent::__construct($integratedShoppingCartId, 'orders/update', $shopifyWebHookLogId);
        $this->jsonShopifyOrder         = $jsonShopifyOrder;
    }


    public function handle()
    {
        $shopifyOrder                   = new ShopifyOrder(json_decode($this->jsonShopifyOrder, true));
        parent::initialize($shopifyOrder->getId());
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $shopifyOrderMappingService     = new ShopifyOrderMappingService($this->integratedShoppingCart->getClient());

        if (!$shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
        {
            $this->shopifyWebHookLog->addNote('shouldImportOrder was false');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
            return;
        }

        $order                          = $shopifyOrderMappingService->handleMapping($shopifyOrder);
        $entityCreated                  = is_null($order->getId()) ? true : false;
        $this->shopifyWebHookLog->setEntityCreated($entityCreated);

        if (!$order->canUpdate())
        {
            $this->shopifyWebHookLog->addNote('order->canUpdate was false');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
            return;
        }

        $orderApprovalService           = new OrderApprovalService();
        $orderApprovalService->processOrder($order);

        $this->orderRepo->saveAndCommit($order);
        $this->shopifyWebHookLog->setEntityId($order->getId());
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }

}