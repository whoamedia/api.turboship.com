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
     * @var ShopifyOrder
     */
    private $shopifyOrder;

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    /**
     * ShopifyCreateOrderJob constructor.
     * @param   ShopifyOrder                $shopifyOrder
     * @param   int                         $integratedShoppingCartId
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($shopifyOrder, $integratedShoppingCartId, $shopifyWebHookLogId = null)
    {
        parent::__construct($integratedShoppingCartId, 'orders/create', $shopifyWebHookLogId);
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
        }
        else
        {
            $order                          = $shopifyOrderMappingService->handleMapping($this->shopifyOrder);
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
