<?php

namespace App\Jobs\Shopify\Products;

use jamesvweston\Shopify\Models\Responses\ShopifyProduct;
use App\Jobs\Shopify\BaseShopifyJob;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Services\Shopify\Mapping\ShopifyProductMappingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ShopifyCreateProductJob extends BaseShopifyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $jsonShopifyProduct;

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * ShopifyImportProductJob constructor.
     * @param   string  $jsonShopifyProduct
     * @param   int                         $integratedShoppingCartId
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($jsonShopifyProduct, $integratedShoppingCartId, $shopifyWebHookLogId = null)
    {
        parent::__construct($integratedShoppingCartId, 'products/create', $shopifyWebHookLogId);
        $this->jsonShopifyProduct       = $jsonShopifyProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shopifyProduct                 = new ShopifyProduct(json_decode($this->jsonShopifyProduct, true));
        parent::initialize($shopifyProduct->getId());
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
        $shopifyProductMappingService   = new ShopifyProductMappingService($this->integratedShoppingCart->getClient());

        if (!$shopifyProductMappingService->shouldImport($shopifyProduct))
        {
            $this->shopifyWebHookLog->addNote('shouldImport Product was false');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
            return;
        }

        $product                        = $shopifyProductMappingService->handleMapping($shopifyProduct);
        $entityCreated                  = is_null($product->getId()) ? true : false;
        $this->shopifyWebHookLog->setEntityCreated($entityCreated);

        $this->productRepo->saveAndCommit($product);

        $this->shopifyWebHookLog->setEntityId($product->getId());
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }
}
