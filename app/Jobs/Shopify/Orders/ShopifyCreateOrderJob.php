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

class ShopifyCreateOrderJob extends BaseShopifyJob implements ShouldQueue
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
     * @var bool
     */
    private $importShippedOrder;


    /**
     * ShopifyCreateOrderJob constructor.
     * @param   string                      $jsonShopifyOrder
     * @param   int                         $integratedShoppingCartId
     * @param   bool                        $importShippedOrder
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($jsonShopifyOrder, $integratedShoppingCartId, $shopifyWebHookLogId = null, $importShippedOrder = false)
    {
        parent::__construct($integratedShoppingCartId, 'orders/create', $shopifyWebHookLogId);

        $this->jsonShopifyOrder         = $jsonShopifyOrder;
        $this->importShippedOrder       = $importShippedOrder;
    }


    public function handle()
    {
        $shopifyOrder                   = new ShopifyOrder(json_decode($this->jsonShopifyOrder, true));
        parent::initialize($shopifyOrder->getId());
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $shopifyOrderMappingService     = new ShopifyOrderMappingService($this->integratedShoppingCart->getClient());

        $shouldImportOrder              = $shopifyOrderMappingService->shouldImportOrder($shopifyOrder);
        $importOverride                 = $shopifyOrderMappingService->shouldImportOrder($shopifyOrder, $this->importShippedOrder);

        if (!$shouldImportOrder)
            $this->shopifyWebHookLog->addNote('shouldImportOrder was false');
        else
        {
            if ($this->importShippedOrder && $importOverride)
                $this->shopifyWebHookLog->addNote('Import shipped order override was successful');

            $order                          = $shopifyOrderMappingService->handleMapping($shopifyOrder);
            $entityCreated                  = is_null($order->getId()) ? true : false;
            $this->shopifyWebHookLog->setEntityCreated($entityCreated);

            $this->orderRepo->saveAndCommit($order);
            $this->shopifyWebHookLog->setEntityId($order->getId());

            $job                        = (new OrderApprovalJob($order->getId()))->onQueue('orderApproval');
            $this->dispatch($job);
        }
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }

}
