<?php

namespace App\Models\Shipments\Validation;


use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipmentValidation
{

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;


    public function __construct()
    {
        $this->shipmentRepo                 = \EntityManager::getRepository('App\Models\Shipments\Shipment');
    }

    /**
     * @param   int     $id
     * @return \App\Models\Shipments\Shipment|null
     */
    public function idExists ($id)
    {
        $shipment                           = $this->shipmentRepo->getOneById($id);
        if (is_null($shipment))
            throw new NotFoundHttpException('Shipment not found');

        return $shipment;
    }
}