<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Jobs\Shopify\Orders\ShopifyCancelOrderJob;
use App\Jobs\Shopify\Orders\ShopifyCreateOrderJob;
use App\Jobs\Shopify\Orders\ShopifyDeleteOrderJob;
use App\Jobs\Shopify\Orders\ShopifyOrderPaidJob;
use App\Jobs\Shopify\Orders\ShopifyUpdateOrderJob;
use Illuminate\Http\Request;

class ShopifyOrderController extends BaseShopifyController
{

    public function __construct ()
    {
        parent::__construct();
    }


    public function createOrder (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $job                            = (new ShopifyCreateOrderJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
            $this->dispatch($job);
        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

    public function cancelOrder (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $job                            = (new ShopifyCancelOrderJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
            $this->dispatch($job);

        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

    public function deleteOrder (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $job                            = (new ShopifyDeleteOrderJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
            $this->dispatch($job);
        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

    public function orderPaid (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $job                            = (new ShopifyOrderPaidJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
            $this->dispatch($job);
        }
        catch (\Exception $exception)
        {
            $this->shopifyWebHookLog->setErrorMessage($exception->getMessage());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
        }

        return response('', 200);
    }

    public function orderUpdated (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $job                            = (new ShopifyUpdateOrderJob($this->json, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
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