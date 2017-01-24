<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Jobs\Shopify\Orders\ShopifyCancelOrderJob;
use App\Jobs\Shopify\Orders\ShopifyCreateOrderJob;
use App\Jobs\Shopify\Orders\ShopifyDeleteOrderJob;
use App\Jobs\Shopify\Orders\ShopifyOrderPaidJob;
use App\Jobs\Shopify\Orders\ShopifyUpdateOrderJob;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Shopify\Mapping\ShopifyOrderMappingService;
use Illuminate\Http\Request;
use EntityManager;

class ShopifyOrderController extends BaseShopifyController
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var ShopifyOrderMappingService
     */
    protected $shopifyOrderMappingService;


    public function __construct ()
    {
        parent::__construct();
        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
    }


    public function createOrder (Request $request)
    {
        try
        {
            parent::handleRequest($request);
            $this->shopifyOrderMappingService   = new ShopifyOrderMappingService($this->client);
            $shopifyOrder                   = new ShopifyOrder($request->input());
            $job                            = (new ShopifyCreateOrderJob($shopifyOrder, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
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
            $this->shopifyOrderMappingService   = new ShopifyOrderMappingService($this->client);
            $shopifyOrder                   = new ShopifyOrder($request->input());
            $job                            = (new ShopifyCancelOrderJob($shopifyOrder, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
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
            $this->shopifyOrderMappingService   = new ShopifyOrderMappingService($this->client);
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $job                            = (new ShopifyDeleteOrderJob($shopifyOrder, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
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
            $this->shopifyOrderMappingService   = new ShopifyOrderMappingService($this->client);
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $job                            = (new ShopifyOrderPaidJob($shopifyOrder, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
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
            $this->shopifyOrderMappingService   = new ShopifyOrderMappingService($this->client);
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $job                            = (new ShopifyUpdateOrderJob($shopifyOrder, $this->integratedShoppingCart->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
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