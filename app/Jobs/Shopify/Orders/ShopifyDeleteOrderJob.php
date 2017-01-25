<?php

namespace App\Jobs\Shopify\Orders;


use jamesvweston\Shopify\Models\Responses\ShopifyOrder;
use App\Jobs\Shopify\BaseShopifyJob;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderStatusService;
use App\Services\Shopify\Mapping\ShopifyOrderMappingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

/**
 * Follows same logic as ShopifyCancelOrderJob
 * Class ShopifyDeleteOrderJob
 * @package App\Jobs\Shopify\Orders
 */
class ShopifyDeleteOrderJob extends BaseShopifyJob implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var ShopifyOrder
     */
    private $shopifyOrder;

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    /**
     * ShopifyDeleteOrderJob constructor.
     * @param   ShopifyOrder                $shopifyOrder
     * @param   int                         $integratedShoppingCartId
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($shopifyOrder, $integratedShoppingCartId, $shopifyWebHookLogId = null)
    {
        parent::__construct($integratedShoppingCartId, 'orders/delete', $shopifyWebHookLogId);
        $this->shopifyOrder             = $shopifyOrder;
    }


    public function handle()
    {
        parent::initialize($this->shopifyOrder->getId());
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $shopifyOrderMappingService     = new ShopifyOrderMappingService($this->integratedShoppingCart->getClient());

        $order                          = $shopifyOrderMappingService->handleMapping($this->shopifyOrder);

        if (is_null($order->getId()))
        {
            //  The order was cancelled in Shopify but does not exist in our system. We shouldn't import it
            $this->shopifyWebHookLog->addNote('Order was deleted in Shopify but does not exist locally');
        }
        else if (!$order->canCancel())
        {
            $this->shopifyWebHookLog->setEntityId($order->getId());
            $this->shopifyWebHookLog->addNote('Order was deleted in Shopify we cannot cancel it locally');
        }
        else
        {
            $this->shopifyWebHookLog->setEntityId($order->getId());
            $orderStatusService             = new OrderStatusService();
            $cancelledStatus                = $orderStatusService->getCancelled();
            $order->addStatus($cancelledStatus);
            $this->orderRepo->saveAndCommit($order);

            $this->shopifyWebHookLog->setEntityId($order->getId());
        }
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }

}