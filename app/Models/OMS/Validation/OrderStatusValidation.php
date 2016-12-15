<?php

namespace App\Models\OMS\Validation;


use App\Models\OMS\OrderStatus;
use App\Repositories\Doctrine\OMS\OrderStatusRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderStatusValidation
{

    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepo;


    public function __construct()
    {
        $this->orderStatusRepo          = EntityManager::getRepository('App\Models\OMS\OrderStatus');
    }


    /**
     * @param   int     $id
     * @return  OrderStatus
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $orderStatus                    = $this->orderStatusRepo->getOneById($id);

        if (is_null($orderStatus))
            throw new NotFoundHttpException('OrderStatus not found');

        return $orderStatus;
    }

}