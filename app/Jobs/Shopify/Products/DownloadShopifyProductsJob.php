<?php

namespace App\Jobs\Shopify\Products;


use App\Jobs\Job;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use App\Repositories\Doctrine\OMS\OrderItemRepository;
use App\Services\Shopify\ShopifyService;
use App\Utilities\SourceUtility;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class DownloadShopifyProductsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;


    /**
     * @var int
     */
    private $integratedShoppingCartId;

    /**
     * @var bool
     */
    private $pendingSku;

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

    /**
     * @var OrderItemRepository
     */
    private $orderItemRepo;

    public function __construct($integratedShoppingCartId, $pendingSku = false)
    {
        parent::__construct();

        $this->integratedShoppingCartId = $integratedShoppingCartId;
        $this->pendingSku               = $pendingSku;
    }

    public function handle ()
    {
        $this->integratedShoppingCartRepo= EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
        $this->integratedShoppingCart   = $this->integratedShoppingCartRepo->getOneById($this->integratedShoppingCartId);
        $this->shopifyService           = new ShopifyService($this->integratedShoppingCart);

        try
        {
            if ($this->pendingSku)
                $this->downloadPendingSku();
            else
                $this->downloadAllProducts();
        }
        catch (\Exception $exception)
        {
            //  Do nothing
        }
    }

    private function downloadPendingSku ()
    {
        $this->orderItemRepo            = EntityManager::getRepository('App\Models\OMS\OrderItem');

        $externalIdsResponse            = $this->orderItemRepo->getPendingExternalProductIds($this->integratedShoppingCart->getClient()->getId(), SourceUtility::SHOPIFY_ID);
        $maxIds                         = 20;
        $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        for ($i = 0; $i < sizeof($externalIdsResponse); $i+=$maxIds)
        {
            set_time_limit(30);
            $externalIds                = array_slice($externalIdsResponse, $i, $maxIds);
            $externalIds                = implode(',', $externalIds);

            $shopifyProductsResponse    = $this->shopifyService->getProductImportCandidates(1, 250, $externalIds);
            $productArray               = json_decode($shopifyProductsResponse, true);
            foreach ($productArray AS $shopifyProduct)
            {
                $job                    = (new ShopifyCreateProductJob(json_encode($shopifyProduct), $this->integratedShoppingCart->getId(), null))->onQueue('shopifyProducts');
                $this->dispatch($job);
            }
            usleep(250000);
        }
    }

    private function downloadAllProducts ()
    {
        $total                          = $this->shopifyService->getProductImportCandidatesCount();
        $totalPages                     = (int)ceil($total / 250);

        $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++)
        {
            set_time_limit(60);
            $shopifyProductsResponse    = $this->shopifyService->getProductImportCandidates($currentPage, 250);
            $productArray               = json_decode($shopifyProductsResponse, true);
            foreach ($productArray AS $shopifyProduct)
            {
                $job                    = (new ShopifyCreateProductJob(json_encode($shopifyProduct), $this->integratedShoppingCart->getId(), null))->onQueue('shopifyProducts');
                $this->dispatch($job);
            }
            usleep(250000);
        }
    }
}