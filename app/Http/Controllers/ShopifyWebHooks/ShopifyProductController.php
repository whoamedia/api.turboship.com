<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Jobs\Shopify\Products\ShopifyCreateProductJob;
use App\Jobs\Shopify\Products\ShopifyDeleteProductJob;
use App\Jobs\Shopify\Products\ShopifyUpdateProductJob;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Services\Shopify\Mapping\ShopifyProductMappingService;
use Illuminate\Http\Request;
use EntityManager;

class ShopifyProductController extends BaseShopifyController
{

    /**
     * @var ShopifyProductMappingService
     */
    private $shopifyProductMappingService;

    /**
     * @var ProductRepository
     */
    private $productRepo;


    public function __construct (Request $request)
    {
        parent::__construct();

        $this->productRepo                  = EntityManager::getRepository('App\Models\OMS\Product');
    }


    public function createProduct (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $this->shopifyProductMappingService = new ShopifyProductMappingService($this->client);
            $shopifyProduct                 = new ShopifyProduct($request->input());

            $job                            = (new ShopifyCreateProductJob($shopifyProduct, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog))->onQueue('shopifyProducts');
            $this->dispatch($job);
        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

    public function deleteProduct (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $this->shopifyProductMappingService = new ShopifyProductMappingService($this->client);
            $shopifyProduct                 = new ShopifyProduct($request->input());

            $job                            = (new ShopifyDeleteProductJob($shopifyProduct, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog))->onQueue('shopifyProducts');
            $this->dispatch($job);
        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

    public function updateProduct (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $this->shopifyProductMappingService = new ShopifyProductMappingService($this->client);
            $shopifyProduct                 = new ShopifyProduct($request->input());

            $job                            = (new ShopifyUpdateProductJob($shopifyProduct, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog))->onQueue('shopifyProducts');
            $this->dispatch($job);
        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

}