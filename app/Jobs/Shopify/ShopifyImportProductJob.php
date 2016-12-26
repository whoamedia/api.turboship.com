<?php

namespace App\Jobs\Shopify;

use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Services\Shopify\Mapping\ShopifyProductMappingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ShopifyImportProductJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ShopifyProduct
     */
    private $shopifyProduct;

    /**
     * @var int
     */
    private $clientId;

    /**
     * @var ClientRepository
     */
    private $clientRepo;

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * ShopifyImportProductJob constructor.
     * @param   ShopifyProduct  $shopifyProduct
     * @param   int             $clientId
     */
    public function __construct($shopifyProduct, $clientId)
    {
        $this->shopifyProduct           = $shopifyProduct;
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
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
        $client                         = $this->clientRepo->getOneById($this->clientId);

        $shopifyProductMappingService   = new ShopifyProductMappingService($client);
        if (!$shopifyProductMappingService->shouldImport($this->shopifyProduct))
            return;

        $product                = $shopifyProductMappingService->handleMapping($this->shopifyProduct);
        $this->productRepo->saveAndCommit($product);
    }
}
