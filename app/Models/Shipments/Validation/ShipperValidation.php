<?php

namespace App\Models\Shipments\Validation;


use App\Models\Shipments\Shipper;
use App\Repositories\Doctrine\Shipments\ShipperRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipperValidation
{

    /**
     * @var ShipperRepository
     */
    private $shipperRepo;


    public function __construct()
    {
        $this->shipperRepo                 = EntityManager::getRepository('App\Models\Shipments\Shipper');
    }

    /**
     * @param   int     $id
     * @return  Shipper|null
     */
    public function idExists ($id)
    {
        $shipper                           = $this->shipperRepo->getOneById($id);

        if (is_null($shipper))
            throw new NotFoundHttpException('Shipper not found');

        return $shipper;
    }

}