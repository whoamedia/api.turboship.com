<?php

namespace App\Models\OMS\Validation;

use App\Models\OMS\OrderSource;
use App\Repositories\Doctrine\OMS\OrderSourceRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderSourceValidation
{

    /**
     * @var OrderSourceRepository
     */
    private $orderSourceRepo;


    public function __construct()
    {
        $this->orderSourceRepo          = EntityManager::getRepository('App\Models\OMS\OrderSource');
    }

    /**
     * @param   int     $id
     * @return  OrderSource
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $orderSource                         = $this->orderSourceRepo->getOneById($id);

        if (is_null($orderSource))
            throw new NotFoundHttpException('OrderSource not found');

        return $orderSource;
    }

}