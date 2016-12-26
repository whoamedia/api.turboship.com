<?php

namespace App\Jobs\Shopify;

use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Doctrine\Logs\ShopifyWebHookLogRepository;
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
     * @var int|null
     */
    private $shopifyWebHookLogId;

    /**
     * @var ClientRepository
     */
    private $clientRepo;

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * @var ShopifyWebHookLogRepository
     */
    private $shopifyWebHookLogRepo;

    /**
     * ShopifyImportProductJob constructor.
     * @param   ShopifyProduct  $shopifyProduct
     * @param   int             $clientId
     * @param   int|null        $shopifyWebHookLogId
     */
    public function __construct($shopifyProduct, $clientId, $shopifyWebHookLogId = null)
    {
        $this->shopifyProduct           = $shopifyProduct;
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
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
        $this->shopifyWebHookLogRepo    = EntityManager::getRepository('App\Models\Logs\ShopifyWebHookLog');
        $shopifyWebHookLog              = is_null($this->shopifyWebHookLogId) ? null : $this->shopifyWebHookLogRepo->getOneById($this->shopifyWebHookLogId);
        $client                         = $this->clientRepo->getOneById($this->clientId);

        $shopifyProductMappingService   = new ShopifyProductMappingService($client);

        if (!is_null($shopifyWebHookLog))
            $shopifyWebHookLog->setExternalId($this->shopifyProduct->getId());

        if (!$shopifyProductMappingService->shouldImport($this->shopifyProduct))
        {
            if (!is_null($shopifyWebHookLog))
            {
                $shopifyWebHookLog->addNote('shouldImportOrder was false');
                $this->shopifyWebHookLogRepo->saveAndCommit($shopifyWebHookLog);
            }
            return;
        }


        $product                        = $shopifyProductMappingService->handleMapping($this->shopifyProduct);
        $entityCreated                  = is_null($product->getId()) ? true : false;

        if (!is_null($shopifyWebHookLog))
            $shopifyWebHookLog->setEntityCreated($entityCreated);

        $this->productRepo->saveAndCommit($product);
        if (!is_null($shopifyWebHookLog))
        {
            $shopifyWebHookLog->setEntityId($product->getId());
            $this->shopifyWebHookLogRepo->saveAndCommit($shopifyWebHookLog);
        }

    }
}
