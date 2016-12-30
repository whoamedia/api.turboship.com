<?php

namespace App\Jobs\Shopify\Products;


use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Jobs\Shopify\BaseShopifyJob;
use App\Models\Logs\ShopifyWebHookLog;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Services\Shopify\Mapping\ShopifyProductMappingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ShopifyUpdateProductJob extends BaseShopifyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ShopifyProduct
     */
    private $shopifyProduct;

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * ShopifyImportProductJob constructor.
     * @param   ShopifyProduct  $shopifyProduct
     * @param   int                         $integratedShoppingCartId
     * @param   ShopifyWebHookLog|null      $shopifyWebHookLog
     */
    public function __construct($shopifyProduct, $integratedShoppingCartId, $shopifyWebHookLog = null)
    {
        parent::__construct($integratedShoppingCartId, 'products/update', $shopifyWebHookLog);
        $this->shopifyProduct           = $shopifyProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        parent::initialize($this->shopifyProduct->getId());
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
        $shopifyProductMappingService   = new ShopifyProductMappingService($this->integratedShoppingCart->getClient());

        $product                        = $shopifyProductMappingService->handleMapping($this->shopifyProduct);
        $entityCreated                  = is_null($product->getId()) ? true : false;
        $this->shopifyWebHookLog->setEntityCreated($entityCreated);

        $this->productRepo->saveAndCommit($product);

        $this->shopifyWebHookLog->setEntityId($product->getId());
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }
}
