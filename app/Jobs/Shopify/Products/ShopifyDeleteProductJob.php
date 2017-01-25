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

class ShopifyDeleteProductJob extends BaseShopifyJob implements ShouldQueue
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
     * @param   string                      $jsonShopifyProduct
     * @param   int                         $integratedShoppingCartId
     * @param   int|null                    $shopifyWebHookLogId
     */
    public function __construct($jsonShopifyProduct, $integratedShoppingCartId, $shopifyWebHookLogId = null)
    {
        parent::__construct($integratedShoppingCartId, 'products/delete', $shopifyWebHookLogId);
        $this->jsonShopifyProduct           = $jsonShopifyProduct;
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

        $product                        = $shopifyProductMappingService->handleMapping($shopifyProduct);
        if (is_null($product->getId()))
        {
            $this->shopifyWebHookLog->addNote('Product was deleted in Shopify but does not exist locally');
        }
        else
        {
            $this->shopifyWebHookLog->setEntityCreated(false);
            $this->shopifyWebHookLog->setEntityId($product->getId());
            // TODO: Set product to inactive
            $this->productRepo->saveAndCommit($product);
            $this->shopifyWebHookLog->addNote('No action taken to delete product');
        }
        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }
}
