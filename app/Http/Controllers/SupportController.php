<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use EntityManager;

class SupportController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\OMS\OrderStatusRepository
     */
    private $orderStatusRepo;

    /**
     * @var \App\Repositories\Doctrine\OMS\CRMSourceRepository
     */
    private $crmSourceRepo;


    public function getCRMSources (Request $request)
    {
        $this->crmSourceRepo            = EntityManager::getRepository('App\Models\OMS\CRMSource');
        return $this->crmSourceRepo->where([], false);
    }

    public function getOrderStatuses (Request $request)
    {
        $this->orderStatusRepo          = EntityManager::getRepository('App\Models\OMS\OrderStatus');
        return $this->orderStatusRepo->where([], false);
    }
}