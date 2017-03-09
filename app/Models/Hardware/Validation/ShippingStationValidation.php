<?php

namespace App\Models\Hardware\Validation;


use App\Models\Hardware\ShippingStation;
use App\Repositories\Doctrine\Hardware\ShippingStationRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingStationValidation
{

    /**
     * @var ShippingStationRepository
     */
    private $shippingStationRepo;


    public function __construct ($shippingStationRepo = null)
    {
        if (is_null($shippingStationRepo))
            $this->shippingStationRepo      = EntityManager::getRepository('App\Models\Hardware\ShippingStation');
        else
            $this->shippingStationRepo      = $shippingStationRepo;
    }

    /**
     * @param   int     $id
     * @return  ShippingStation
     */
    public function idExists ($id)
    {
        $shippingStation                    = $this->shippingStationRepo->getOneById($id);

        if (is_null($shippingStation))
            throw new NotFoundHttpException('ShippingStation not found');

        return $shippingStation;
    }
}