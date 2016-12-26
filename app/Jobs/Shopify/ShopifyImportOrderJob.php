<?php

namespace App\Jobs\Shopify;

use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Jobs\Orders\OrderApprovalJob;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Doctrine\Logs\ShopifyWebHookLogRepository;
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
     * @var int|null
     */
    private $shopifyWebHookLogId;

    /**
     * @var ClientRepository
     */
    private $clientRepo;

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var ShopifyWebHookLogRepository
     */
    private $shopifyWebHookLogRepo;


    /**
     * ShopifyImportOrderJob constructor.
     * @param   ShopifyOrder    $shopifyOrder
     * @param   int             $clientId
     * @param   int|null        $shopifyWebHookLogId
     */
    public function __construct($shopifyOrder, $clientId, $shopifyWebHookLogId = null)
    {
        $this->shopifyOrder             = $shopifyOrder;
        $this->clientId                 = $clientId;
        $this->shopifyWebHookLogId      = $shopifyWebHookLogId;
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
        $this->shopifyWebHookLogRepo    = EntityManager::getRepository('App\Models\Logs\ShopifyWebHookLog');
        $client                         = $this->clientRepo->getOneById($this->clientId);
        $shopifyWebHookLog              = is_null($this->shopifyWebHookLogId) ? null : $this->shopifyWebHookLogRepo->getOneById($this->shopifyWebHookLogId);
        $shopifyOrderMappingService     = new ShopifyOrderMappingService($client);

        if (!is_null($shopifyWebHookLog))
            $shopifyWebHookLog->setExternalId($this->shopifyOrder->getId());

        if (!$shopifyOrderMappingService->shouldImportOrder($this->shopifyOrder))
        {
            if (!is_null($shopifyWebHookLog))
            {
                $shopifyWebHookLog->addNote('shouldImportOrder was false');
                $this->shopifyWebHookLogRepo->saveAndCommit($shopifyWebHookLog);
            }
            return;
        }

        $order                          = $shopifyOrderMappingService->handleMapping($this->shopifyOrder);
        $entityCreated                  = is_null($order->getId()) ? true : false;

        if (!is_null($shopifyWebHookLog))
            $shopifyWebHookLog->setEntityCreated($entityCreated);

        $this->orderRepo->saveAndCommit($order);
        if (!is_null($shopifyWebHookLog))
        {
            $shopifyWebHookLog->setEntityId($order->getId());
            $this->shopifyWebHookLogRepo->saveAndCommit($shopifyWebHookLog);
        }
        $job                        = (new OrderApprovalJob($order->getId()))->onQueue('orderApproval');
        $this->dispatch($job);
    }
}
