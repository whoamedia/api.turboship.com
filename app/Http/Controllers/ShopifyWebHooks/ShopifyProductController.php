<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
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
        parent::__construct($request);

        $this->shopifyProductMappingService = new ShopifyProductMappingService($this->client);
        $this->productRepo                  = EntityManager::getRepository('App\Models\OMS\Product');
    }


    public function createProduct (Request $request)
    {
        try
        {
            $shopifyProduct                 = new ShopifyProduct($request->input());

            if (!$this->shopifyProductMappingService->shouldImport($shopifyProduct))
                return response('', 200);

            $product                        = $this->shopifyProductMappingService->handleMapping($shopifyProduct);
            $this->productRepo->saveAndCommit($product);
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function deleteProduct (Request $request)
    {
        try
        {
            //  TODO: Figure out deletion
            $shopifyProduct                 = new ShopifyProduct($request->input());
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function updateProduct (Request $request)
    {
        try
        {
            $shopifyProduct                 = new ShopifyProduct($request->input());

            //  TODO: The product may exist in turboShip and may need to be set it inactive
            if (!$this->shopifyProductMappingService->shouldImport($shopifyProduct))
                return response('', 200);

            $product                        = $this->shopifyProductMappingService->handleMapping($shopifyProduct);
            $this->productRepo->saveAndCommit($product);
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

}