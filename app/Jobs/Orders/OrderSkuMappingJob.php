<?php

namespace App\Jobs\Orders;

use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderApprovalService;
use App\Utilities\OrderStatusUtility;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class OrderSkuMappingJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * @var int
     */
    private $clientId;

    /**
     * @var string
     */
    private $variantSku;

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var OrderApprovalService
     */
    private $orderApprovalService;

    public function __construct($clientId, $variantSku)
    {
        $this->clientId                 = $clientId;
        $this->variantSku               = $variantSku;
    }


    public function handle()
    {
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderApprovalService     = new OrderApprovalService();

        $orderQuery     = [
            'clientIds'                 => $this->clientId,
            'statusIds'                 => OrderStatusUtility::UNMAPPED_SKU,
            'itemSkus'                  => $this->variantSku,
        ];

        $result                         = $this->orderRepo->where($orderQuery);
        foreach ($result AS $order)
        {
            $this->orderApprovalService->processOrder($order);
            $this->orderRepo->saveAndCommit($order);
        }
    }
}
