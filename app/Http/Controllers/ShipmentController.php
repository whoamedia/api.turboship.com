<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shipments\CreateShipmentsJob;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\Shipments\CreateShipmentsService;
use Illuminate\Http\Request;
use EntityManager;

class ShipmentController
{

    /**
     * @var ClientValidation
     */
    private $clientValidation;

    /**
     * @var ShipperValidation
     */
    private $shipperValidation;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;


    public function __construct()
    {
        $this->clientValidation         = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));
        $this->shipperValidation        = new ShipperValidation();
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
    }






    public function createShipmentsJob (Request $request)
    {
        $createShipmentsJob             = new CreateShipmentsJob($request->input());
        $createShipmentsJob->validate();
        $createShipmentsJob->clean();

        $client                         = $this->clientValidation->idExists($createShipmentsJob->getClientId());
        $shipper                        = $this->shipperValidation->idExists($createShipmentsJob->getShipperId());

        $createShipmentsService         = new CreateShipmentsService($client, $shipper);
        $shipments                      = $createShipmentsService->runOnePerOrder();

        foreach ($shipments AS $shipment)
            $this->shipmentRepo->saveAndCommit($shipment);

        return response ($shipments, 201);
    }
}