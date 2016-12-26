<?php

namespace App\Http\Controllers;


use App\Http\Requests\Orders\GetOrders;
use App\Http\Requests\Orders\ShowOrder;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderApprovalService;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    public function __construct ()
    {
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
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