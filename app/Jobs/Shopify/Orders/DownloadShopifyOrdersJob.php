<?php

namespace App\Jobs\Shopify\Orders;


use App\Jobs\Job;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use App\Services\Shopify\ShopifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class DownloadShopifyOrdersJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;


    /**
     * @var int
     */
    private $integratedShoppingCartId;

    /**
     * @var bool
     */
    private $shippedOrders;

    /**
     * @var IntegratedShoppingCartRepository
     */
    private $integratedShoppingCartRepo;

    /**
     * @var IntegratedShoppingCart
     */
    private $integratedShoppingCart;

    /**
     * @var ShopifyService
     */
    private $shopifyService;


    public function __construct($integratedShoppingCartId, $shippedOrders = false)
    {
        parent::__construct();

        $this->integratedShoppingCartId = $integratedShoppingCartId;
        $this->shippedOrders            = $shippedOrders;
    }


    public function handle ()
    {
        $this->integratedShoppingCartRepo= EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
        $this->integratedShoppingCart   = $this->integratedShoppingCartRepo->getOneById($this->integratedShoppingCartId);
        $this->shopifyService           = new ShopifyService($this->integratedShoppingCart);

        if ($this->shippedOrders)
            $this->downloadShippedOrders();
        else
            $this->downloadPendingOrders();
    }


    private function downloadPendingOrders ()
    {
        $total                              = $this->shopifyService->getOrderImportCandidatesCount();
        $totalPages                         = (int)ceil($total / 250);

        $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++)
        {
            set_time_limit(60);
            $shopifyOrdersResponse          = $this->shopifyService->getOrderImportCandidates($currentPage, 250);
            $orderArray                     = json_decode($shopifyOrdersResponse, true);
            foreach ($orderArray AS $shopifyOrder)
            {
                $job                        = (new ShopifyCreateOrderJob(json_encode($shopifyOrder), $this->integratedShoppingCart->getId()))->onQueue('shopifyOrders');
                $this->dispatch($job);
            }
            usleep(250000);
        }
    }

    private function downloadShippedOrders ()
    {
        $total                              = $this->shopifyService->getOrdersShippedCount();
        $totalPages                         = (int)ceil($total / 250);


        $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        for ($page = 1; $page <= $totalPages; $page++)
        {
            set_time_limit(60);
            $shopifyOrdersResponse          = $this->shopifyService->getOrdersShipped($page, 250);
            $orderArray                     = json_decode($shopifyOrdersResponse, true);
            foreach ($orderArray AS $shopifyOrder)
            {
                $job                        = (new ShopifyCreateOrderJob(json_encode($shopifyOrder), $this->integratedShoppingCart->getId(), $this->shippedOrders))->onQueue('shopifyOrders');
                $this->dispatch($job);
            }
        }
    }

}