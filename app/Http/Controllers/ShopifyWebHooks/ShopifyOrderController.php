<?php

namespace App\Http\Controllers\ShopifyWebHooks;


use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Jobs\Shopify\ShopifyImportOrderJob;
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
            $job                            = (new ShopifyImportOrderJob($shopifyOrder, $this->client->getId(), $this->shopifyWebHookLog->getId()))->onQueue('shopifyOrders');
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
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $this->shopifyWebHookLog->setExternalId($shopifyOrder->getId());
            $this->shopifyWebHookLog->addNote('No action taken to cancel order');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);

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
            $shopifyOrder                   = new ShopifyOrder($request->input());

            $this->shopifyWebHookLog->setExternalId($shopifyOrder->getId());
            $this->shopifyWebHookLog->addNote('No action taken to delete order');
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
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
            $shopifyOrder                   = new ShopifyOrder($request->input());
            $this->shopifyWebHookLog->setExternalId($shopifyOrder->getId());

            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
            {
                $this->shopifyWebHookLog->addNote('shouldImportOrder was false');
                $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
                return response('', 200);
            }

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);

            $entityCreated                  = is_null($order->getId()) ? true : false;
            $this->shopifyWebHookLog->setEntityCreated($entityCreated);

            if (!is_null($order->getId()))
            {
                $this->shopifyWebHookLog->setEntityId($order->getId());
                if (!$order->canUpdate())
                {
                    $this->shopifyWebHookLog->addNote('$order->canUpdate was false');
                    $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
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

            $this->shopifyWebHookLog->setEntityId($order->getId());
            $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
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
            $shopifyOrder                   = new ShopifyOrder($request->input());
            $this->shopifyWebHookLog->setExternalId($shopifyOrder->getId());

            if (!$this->shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
            {
                $this->shopifyWebHookLog->addNote('shouldImportOrder was false');
                $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
                return response('', 200);
            }

            $order                          = $this->shopifyOrderMappingService->handleMapping($shopifyOrder);

            $entityCreated                  = is_null($order->getId()) ? true : false;
            $this->shopifyWebHookLog->setEntityCreated($entityCreated);

            if (!is_null($order->getId()))
            {
                $this->shopifyWebHookLog->setEntityId($order->getId());
                if (!$order->canUpdate())
                {
                    $this->shopifyWebHookLog->addNote('order->canUpdate was false');
                    $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
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

            $this->shopifyWebHookLog->setEntityId($order->getId());
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