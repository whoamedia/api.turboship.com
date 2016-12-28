<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shipments\CreateShipmentsJob;
use App\Http\Requests\Shipments\GetShipments;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Shipments\Shipment;
use App\Models\Shipments\Validation\ShipmentValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\Shipments\CreateShipmentsService;
use Illuminate\Http\Request;
use EntityManager;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShipmentController extends BaseAuthController
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


    public function index (Request $request)
    {
        $getShipments                   = new GetShipments($request->input());
        $getShipments->setOrganizationIds($this->getAuthUserOrganization()->getId());
        $getShipments->validate();
        $getShipments->clean();

        $query                          = $getShipments->jsonSerialize();
        $shipments                      = $this->shipmentRepo->where($query, false);
        return response($shipments);
    }

    public function show (Request $request)
    {
        $shipment                       = $this->getShipment($request->route('id'));
        return response($shipment);
    }


    public function createShipmentsJob (Request $request)
    {
        $createShipmentsJob             = new CreateShipmentsJob($request->input());
        $createShipmentsJob->validate();
        $createShipmentsJob->clean();

        $client                         = $this->clientValidation->idExists($createShipmentsJob->getClientId());
        $shipper                        = $this->shipperValidation->idExists($createShipmentsJob->getShipperId());

        set_time_limit(120);
        $createShipmentsService         = new CreateShipmentsService($client, $shipper);
        $shipments                      = $createShipmentsService->runOnePerOrder();

        foreach ($shipments AS $shipment)
        {
            set_time_limit(60);
            $this->shipmentRepo->saveAndCommit($shipment);
        }

        return response ($shipments, 201);
    }


    /**
     * @param   int         $id
     * @return  Shipment
     */
    private function getShipment ($id)
    {
        if (is_null($id))
            throw new BadRequestHttpException('id is required');
        else if (is_null(InputUtil::getInt($id)))
            throw new BadRequestHttpException('id must be integer');

        $shipmentValidation             = new ShipmentValidation();

        $shipment                       = $shipmentValidation->idExists($id);
        return $shipment;
    }
}