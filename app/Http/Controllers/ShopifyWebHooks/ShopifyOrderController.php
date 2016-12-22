<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Shopify\ShopifyOrderRepository;
use App\Services\Shopify\ShopifyMappingService;
use App\Services\Shopify\ShopifyOrderService;
use Illuminate\Http\Request;
use EntityManager;

class ShopifyOrderController extends BaseShopifyController
{

    /**
     * @var ShopifyOrderService
     */
    private $shopifyOrderService;

    /**
     * @var ShopifyMappingService
     */
    private $shopifyMappingService;

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var ShopifyOrderRepository
     */
    private $shopifyOrderReposity;


    public function __construct (Request $request)
    {
        parent::__construct($request);

        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->shopifyOrderService          = new ShopifyOrderService($this->clientIntegration);
        $this->shopifyMappingService        = new ShopifyMappingService();
        $this->shopifyOrderReposity         = new ShopifyOrderRepository($this->clientIntegration);
    }


    public function createOrder (Request $request)
    {
        try
        {
            $shopifyOrder                   = new ShopifyOrder($request->input());

            if ($this->shopifyOrderReposity->shouldImport($shopifyOrder))
            {
                $order                      = $this->shopifyOrderService->getOrder($shopifyOrder);
                //  TODO: Check to see if the order is being fulfilled
            }
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function cancelOrder (Request $request)
    {
        try
        {
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $order                          = $this->shopifyOrderService->getOrder($shopifyOrder);
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function deleteOrder (Request $request)
    {
        try
        {
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $order                          = $this->shopifyOrderService->getOrder($shopifyOrder);
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function orderPaid (Request $request)
    {
        try
        {
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $order                          = $this->shopifyOrderService->getOrder($shopifyOrder);
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

    public function orderUpdated (Request $request)
    {
        try
        {
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $order                          = $this->shopifyOrderService->getOrder($shopifyOrder);
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

}