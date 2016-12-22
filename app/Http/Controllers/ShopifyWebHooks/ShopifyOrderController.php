<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderApprovalService;
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
            $this->webHookLog->setExternalId($shopifyOrder->getId());

            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
            {
                $this->webHookLog->addNote('shouldImportOrder was false');
                $this->webHookLogRepo->saveAndCommit($this->webHookLog);
                return response('', 200);
            }

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);

            $orderApprovalService           = new OrderApprovalService();
            $orderApprovalService->processOrder($order);

            $this->orderRepo->saveAndCommit($order);

            $this->webHookLog->setEntityId($order->getId());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
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

            $this->webHookLog->setExternalId($shopifyOrder->getId());
            $this->webHookLog->addNote('No action taken to cancel order');
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);

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

            $this->webHookLog->setExternalId($shopifyOrder->getId());
            $this->webHookLog->addNote('No action taken to delete order');
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
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
            $this->webHookLog->setExternalId($shopifyOrder->getId());

            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
            {
                $this->webHookLog->addNote('shouldImportOrder was false');
                $this->webHookLogRepo->saveAndCommit($this->webHookLog);
                return response('', 200);
            }

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);

            if (!is_null($order->getId()))
            {
                $this->webHookLog->setEntityId($order->getId());
                if (!$order->canUpdate())
                {
                    $this->webHookLog->addNote('$order->canUpdate was false');
                    $this->webHookLogRepo->saveAndCommit($this->webHookLog);
                    // TODO: Log that the CRM cancelled the Order but we cannot cancel it internall
                    return response('', 200);
                }

                if ($order->canRunApprovalProcess())
                {
                    $orderApprovalService   = new OrderApprovalService();
                    $orderApprovalService->processOrder($order);
                }
            }

            $this->orderRepo->saveAndCommit($order);

            $this->webHookLog->setEntityId($order->getId());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
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
            $this->webHookLog->setExternalId($shopifyOrder->getId());

            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
            {
                $this->webHookLog->addNote('shouldImportOrder was false');
                $this->webHookLogRepo->saveAndCommit($this->webHookLog);
                return response('', 200);
            }

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);

            if (!is_null($order->getId()))
            {
                $this->webHookLog->setEntityId($order->getId());
                if (!$order->canUpdate())
                {
                    $this->webHookLog->addNote('order->canUpdate was false');
                    $this->webHookLogRepo->saveAndCommit($this->webHookLog);
                    // TODO: Log that the CRM cancelled the Order but we cannot cancel it internall
                    return response('', 200);
                }

                if ($order->canRunApprovalProcess())
                {
                    $orderApprovalService   = new OrderApprovalService();
                    $orderApprovalService->processOrder($order);
                }
            }

            $this->orderRepo->saveAndCommit($order);

            $this->webHookLog->setEntityId($order->getId());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);

        }
        catch (\Exception $exception)
        {
            $this->webHookLog->setErrorMessage($exception->getMessage());
            $this->webHookLogRepo->saveAndCommit($this->webHookLog);
        }

        return response('', 200);
    }

}