<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use EntityManager;

class SupportController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\Support\SourceRepository
     */
    private $sourceRepo;

    /**
     * @var \App\Repositories\Doctrine\OMS\OrderStatusRepository
     */
    private $orderStatusRepo;

    /**
     * @var \App\Repositories\Doctrine\Locations\SubdivisionTypeRepository
     */
    private $subdivisionTypeRepo;


    public function getSources (Request $request)
    {
        $this->sourceRepo            = EntityManager::getRepository('App\Models\Support\Source');
        return $this->sourceRepo->where([], false);
    }

    public function getOrderStatuses (Request $request)
    {
        $this->orderStatusRepo          = EntityManager::getRepository('App\Models\OMS\OrderStatus');
        return $this->orderStatusRepo->where([], false);
    }

    public function getSubdivisionTypes (Request $request)
    {
        $this->subdivisionTypeRepo      = EntityManager::getRepository('App\Models\Locations\SubdivisionType');
        return $this->subdivisionTypeRepo->where([], false);
    }
}