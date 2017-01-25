<?php

namespace App\Http\Controllers;


use App\Http\Requests\Addresses\UpdateAddress;
use App\Http\Requests\Orders\GetOrders;
use App\Http\Requests\Orders\ShowOrder;
use App\Jobs\Orders\OrderApprovalJob;
use App\Models\OMS\Order;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Address\AddressService;
use App\Services\Order\OrderApprovalService;
use App\Utilities\CountryUtility;
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

        if ($getOrders->getIsSkuError() == true)
            $getOrders->setStatusIds(OrderStatusUtility::UNMAPPED_SKU);

        if ($getOrders->getIsAddressError() == true)
            $getOrders->setStatusIds(implode(',', OrderStatusUtility::getAddressErrors()));

        $query                          = $getOrders->jsonSerialize();
        $results                        = $this->orderRepo->where($query, false);
        return response($results);
    }


    public function getLexicon (Request $request)
    {
        $getOrders                      = new GetOrders($request->input());
        $getOrders->setOrganizationIds(\Auth::getUser()->getOrganization()->getId());
        $getOrders->validate();
        $getOrders->clean();

        if ($getOrders->getIsSkuError() == true)
            $getOrders->setStatusIds(OrderStatusUtility::UNMAPPED_SKU);

        if ($getOrders->getIsAddressError() == true)
            $getOrders->setStatusIds(implode(',', OrderStatusUtility::getAddressErrors()));

        $query                          = $getOrders->jsonSerialize();
        $results                        = $this->orderRepo->getLexicon($query);
        return response($results);
    }

    public function show (Request $request)
    {
        $order                          = $this->getOrderFromRoute($request->route('id'));
        return response($order);
    }

    public function updateShippingAddress (Request $request)
    {
        $order                          = $this->getOrderFromRoute($request->route('id'));



        $test = [];

        if (config('turboship.address.usps.validationEnabled') == false)
            $test['config'] = 'returning because config';

        if ($order->getShippingAddress()->getCountry()->getId() != CountryUtility::UNITED_STATES)
            $test['country'] = 'returning because country';

        return response($test);
        if (!$order->canUpdate())
            throw new BadRequestHttpException('Order is in a status that cannot be updated');

        $updateAddress                  = new UpdateAddress($request->input());
        $updateAddress->setId($order->getShippingAddress()->getId());
        $updateAddress->validate();
        $updateAddress->clean();

        $addressService                 = new AddressService();
        $address                        = $addressService->updateAddress($order->getShippingAddress(), $updateAddress);
        $address->validate();

        $order->setShippingAddress($address);

        $orderApprovalService           = new OrderApprovalService();
        $order                          = $orderApprovalService->processOrder($order);


        $this->orderRepo->saveAndCommit($order);
        return response($order);
    }

    public function getStatusHistory (Request $request)
    {
        $order                          = $this->getOrderFromRoute($request->route('id'));
        return response($order->getStatusHistory());
    }


    public function approveIndividualOrder (Request $request)
    {
        $order                          = $this->getOrderFromRoute($request->route('id'));

        if (!$order->canRunApprovalProcess())
            throw new BadRequestHttpException('The order is in a status where it cannot be put through the approval process');

        $orderApprovalService           = new OrderApprovalService();
        $orderApprovalService->processOrder($order);

        $this->orderRepo->saveAndCommit($order);
        return response($order);
    }


    public function approveOrders (Request $request)
    {
        $getOrders                      = new GetOrders($request->input());
        $getOrders->setOrganizationIds(\Auth::getUser()->getOrganization()->getId());
        $getOrders->validate();
        $getOrders->clean();

        if ($getOrders->getIsSkuError() == true)
            $getOrders->setStatusIds(OrderStatusUtility::UNMAPPED_SKU);

        if ($getOrders->getIsAddressError() == true)
            $getOrders->setStatusIds(implode(',', OrderStatusUtility::getAddressErrors()));

        $query                          = $getOrders->jsonSerialize();

        $results                        = $this->orderRepo->where($query);

        foreach ($results AS $order)
        {
            $job                        = (new OrderApprovalJob($order->getId()))->onQueue('orderApproval');
            $this->dispatch($job);
        }
    }

    /**
     * @param   int     $id
     * @return  Order
     */
    private function getOrderFromRoute ($id)
    {
        $showOrder                      = new ShowOrder();
        $showOrder->setId($id);
        $showOrder->validate();
        $showOrder->clean();

        $order                          = $this->orderRepo->getOneById($showOrder->getId());
        if (is_null($order))
            throw new NotFoundHttpException('Order not found');

        return $order;
    }
}