<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
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


    public function __construct (Request $request)
    {
        parent::__construct($request);

        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->shopifyOrderMappingService   = new ShopifyOrderMappingService($this->client);
    }


    public function createOrder (Request $request)
    {
        try
        {
            $shopifyOrder                   = new ShopifyOrder($request->input());
            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
                return response('', 200);

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);
            $this->orderRepo->saveAndCommit($order);
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
            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
                return response('', 200);

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);

            if (!is_null($order->getId()))
            {
                if (!$order->canUpdate())
                {
                    // TODO: Log that the CRM cancelled the Order but we cannot cancel it internall
                    return response('', 200);
                }
            }

            $this->orderRepo->saveAndCommit($order);
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
            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
                return response('', 200);

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);

            if (!is_null($order->getId()))
            {
                if (!$order->canUpdate())
                {
                    // TODO: Log that the CRM cancelled the Order but we cannot cancel it internall
                    return response('', 200);
                }
            }

            $this->orderRepo->saveAndCommit($order);
        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

}