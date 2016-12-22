<?php

namespace App\Http\Controllers;


use App\Http\Requests\Orders\GetOrders;
use App\Repositories\Doctrine\OMS\OrderRepository;
use Illuminate\Http\Request;
use EntityManager;

class OrderController extends Controller
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    /**
     * ClientController constructor.
     */
    public function __construct ()
    {
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
    }


    public function index (Request $request)
    {
        $getOrders                      = new GetOrders($request->input());
        $getOrders->validate();
        $getOrders->clean();

        $query                          = $getOrders->jsonSerialize();

        $results                        = $this->orderRepo->where($query, false);
        return response($results);
    }
}