<?php

namespace App\Http\Controllers;


use App\Http\Requests\Orders\GetErrorOrders;
use App\Http\Requests\Orders\GetOrders;
use App\Http\Requests\Orders\ShowOrder;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\OMS\OrderStatusRepository;
use App\Services\Order\OrderApprovalService;
use App\Utilities\OrderStatusUtility;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends BaseAuthController
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepo;

    public function __construct ()
    {
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderStatusRepo          = EntityManager::getRepository('App\Models\OMS\OrderStatus');
    }


    public function index (Request $request)
    {
        $getOrders                      = new GetOrders($request->input());
        $getOrders->setOrganizationIds(\Auth::getUser()->getOrganization()->getId());
        $getOrders->validate();
        $getOrders->clean();

        $query                          = $getOrders->jsonSerialize();

        $results                        = $this->orderRepo->where($query, false);
        return response($results);
    }

    public function show (Request $request)
    {
        $showOrder                      = new ShowOrder();
        $showOrder->setId($request->route('id'));
        $showOrder->validate();
        $showOrder->clean();

        $order                          = $this->orderRepo->getOneById($showOrder->getId());
        if (is_null($order))
            throw new NotFoundHttpException('Order not found');

        return response($order);
    }


    public function getErrorOrders (Request $request)
    {
        $getErrorOrders                 = new GetErrorOrders($request->input());
        $getErrorOrders->setOrganizationIds($this->getAuthUserOrganization()->getId());
        $getErrorOrders->validate();
        $getErrorOrders->clean();

        if ($getErrorOrders->getSkuError() == true)
            $getErrorOrders->setStatusIds(OrderStatusUtility::UNMAPPED_SKU);

        if ($getErrorOrders->getAddressError() == true)
            $getErrorOrders->setStatusIds(implode(',', OrderStatusUtility::getAddressErrors()));

        $query                          = $getErrorOrders->jsonSerialize();
        $orders                         = $this->orderRepo->where($query, false);
        return response($orders);
    }




    public function getStatuses (Request $request)
    {
        $orderStatuses                  = $this->orderStatusRepo->where([], false);
        return response($orderStatuses);
    }

    public function approveIndividualOrder (Request $request)
    {
        $showOrder                      = new ShowOrder();
        $showOrder->setId($request->route('id'));
        $showOrder->validate();
        $showOrder->clean();

        $order                          = $this->orderRepo->getOneById($showOrder->getId());
        if (is_null($order))
            throw new NotFoundHttpException('Order not found');

        if (!$order->canRunApprovalProcess())
            throw new BadRequestHttpException('The order is in a status where it cannot be put through the approval process');

        $orderApprovalService           = new OrderApprovalService();
        $orderApprovalService->processOrder($order);

        $this->orderRepo->saveAndCommit($order);
        return response($order);
    }

}