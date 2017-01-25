<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Jobs\Shopify\Products\ShopifyCreateProductJob;
use App\Jobs\Shopify\Products\ShopifyDeleteProductJob;
use App\Jobs\Shopify\Products\ShopifyUpdateProductJob;
use Illuminate\Http\Request;

class ShopifyProductController extends BaseShopifyController
{



    public function __construct (Request $request)
    {
        parent::__construct();
    }


    public function createProduct (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $job                            = (new ShopifyCreateProductJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyProducts');
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
            $job                            = (new ShopifyDeleteProductJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyProducts');
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
            $job                            = (new ShopifyUpdateProductJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyProducts');
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