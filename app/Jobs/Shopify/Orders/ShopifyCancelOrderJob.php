<?php

namespace App\Jobs\Shopify\Orders;


use jamesvweston\Shopify\Models\Responses\ShopifyOrder;
use App\Jobs\Shopify\BaseShopifyJob;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderStatusService;
use App\Services\Shopify\Mapping\ShopifyOrderMappingService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ShopifyCancelOrderJob extends BaseShopifyJob implements ShouldQueue
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
     * ShopifyCancelOrderJob constructor.
     * @param   string                      $jsonShopifyOrder
     * @param   int                         $integratedShoppingCartId
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($jsonShopifyOrder, $integratedShoppingCartId, $shopifyWebHookLogId = null)
    {
        parent::__construct($integratedShoppingCartId, 'orders/cancel', $shopifyWebHookLogId);
        $this->jsonShopifyOrder          = $jsonShopifyOrder;
    }


    public function handle()
    {
        $shopifyOrder                   = new ShopifyOrder(json_decode($this->jsonShopifyOrder, true));
        parent::initialize($shopifyOrder->getId());
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $shopifyOrderMappingService     = new ShopifyOrderMappingService($this->integratedShoppingCart->getClient());

        $order                          = $shopifyOrderMappingService->handleMapping($shopifyOrder);
        if (is_null($order->getId()))
        {
            //  The order was cancelled in Shopify but does not exist in our system. We shouldn't import it
            $this->shopifyWebHookLog->addNote('Order was cancelled in Shopify but does not exist locally');
        }
        else if (!$order->canCancel())
        {
            $this->shopifyWebHookLog->addNote('Order was cancelled in Shopify we cannot cancel it locally');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }
        else
        {
            $orderStatusService             = new OrderStatusService();
            $cancelledStatus                = $orderStatusService->getCancelled();
            $order->addStatus($cancelledStatus);
            $this->orderRepo->saveAndCommit($order);

            $this->shopifyWebHookLog->setEntityId($order->getId());
        }
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }

}