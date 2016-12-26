<?php

namespace App\Jobs\Shopify;

use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Jobs\Orders\OrderApprovalJob;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Shopify\Mapping\ShopifyOrderMappingService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ShopifyImportOrderJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;


    /**
     * @var ShopifyOrder
     */
    private $shopifyOrder;

    /**
     * @var int
     */
    private $clientId;

    /**
     * @var ClientRepository
     */
    private $clientRepo;

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    public function __construct($shopifyOrder, $clientId)
    {
        $this->shopifyOrder             = $shopifyOrder;
        $this->clientId                 = $clientId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $client                         = $this->clientRepo->getOneById($this->clientId);

        $shopifyOrderMappingService     = new ShopifyOrderMappingService($client);
        if (!$shopifyOrderMappingService->shouldImportOrder($this->shopifyOrder))
            return;

        $order                          = $shopifyOrderMappingService->handleMapping($this->shopifyOrder);
        $this->orderRepo->saveAndCommit($order);
        $job                        = (new OrderApprovalJob($order->getId()))->onQueue('orderApproval');
        $this->dispatch($job);
    }
}
