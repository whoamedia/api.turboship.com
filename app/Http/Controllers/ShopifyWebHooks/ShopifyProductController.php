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

            $this->shopifyWebHookLog->setExternalId($shopifyProduct->getId());

            if (!$this->shopifyProductMappingService->shouldImport($shopifyProduct))
            {
                $this->shopifyWebHookLog->addNote('shouldImport was false');
                $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
                return response('', 200);
            }

            $product                        = $this->shopifyProductMappingService->handleMapping($shopifyProduct);
            $this->productRepo->saveAndCommit($product);

            $this->shopifyWebHookLog->setEntityId($product->getId());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
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
            //  TODO: Figure out deletion
            $shopifyProduct                 = new ShopifyProduct($request->input());

            $this->shopifyWebHookLog->setExternalId($shopifyProduct->getId());
            $this->shopifyWebHookLog->addNote('No action taken to delete product');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);

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
            $shopifyProduct                 = new ShopifyProduct($request->input());

            $this->shopifyWebHookLog->setExternalId($shopifyProduct->getId());

            //  TODO: The product may exist in turboShip and may need to be set it inactive
            if (!$this->shopifyProductMappingService->shouldImport($shopifyProduct))
            {
                $this->shopifyWebHookLog->addNote('shouldImport was false');
                $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
                return response('', 200);
            }

            $product                        = $this->shopifyProductMappingService->handleMapping($shopifyProduct);
            $this->productRepo->saveAndCommit($product);

            $this->shopifyWebHookLog->setEntityId($product->getId());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

}