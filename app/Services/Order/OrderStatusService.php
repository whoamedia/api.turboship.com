<?php

namespace App\Services\Order;


use App\Models\OMS\OrderStatus;
use App\Models\OMS\Validation\OrderStatusValidation;
use App\Utilities\OrderStatusUtility;

class OrderStatusService
{


    /**
     * @var OrderStatusValidation
     */
    private $orderStatusValidation;


    public function __construct()
    {
        $this->orderStatusValidation    = new OrderStatusValidation();
    }

    /**
     * @return OrderStatus
     */
    public function getCancelled ()
    {
        return $this->orderStatusValidation->idExists(OrderStatusUtility::CANCELLED);
    }
}