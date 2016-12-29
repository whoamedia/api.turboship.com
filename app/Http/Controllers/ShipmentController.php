<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shipments\RateShipment;
use App\Http\Requests\Shipments\CreateShipmentsJob;
use App\Http\Requests\Shipments\GetShipments;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Shipments\Shipment;
use App\Models\Shipments\Validation\ShipmentValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Repositories\EasyPost\EasyPostShipmentRepository;
use App\Services\EasyPost\Mapping\EasyPostShipmentMappingService;
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

    /**
     * @var IntegratedShippingApiRepository
     */
    private $integratedShippingApiRepo;


    public function __construct()
    {
        $this->clientValidation         = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));
        $this->shipperValidation        = new ShipperValidation();
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
        $this->integratedShippingApiRepo= EntityManager::getRepository('App\Models\Integrations\IntegratedShippingApi');
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



    public function rate (Request $request)
    {
        $rateShipment                  = new RateShipment($request->input());
        $rateShipment->setId($request->route('id'));
        $rateShipment->validate();
        $rateShipment->clean();

        $shipment                       = $this->getShipment($rateShipment->getId());

        $shippingContainerValidation    = new ShippingContainerValidation();
        $shippingContainer              = $shippingContainerValidation->idExists($rateShipment->getShippingContainerId());
        $shipment->setShippingContainer($shippingContainer);

        $weight                         = $rateShipment->getWeight();
        $shipment->setWeight($weight);


        $integratedShippingApi          = $this->integratedShippingApiRepo->getOneById($rateShipment->getIntegratedShippingApiId());
        $easyPostShipmentRepo           = new EasyPostShipmentRepository($integratedShippingApi);

        $easyPostShipmentMappingService = new EasyPostShipmentMappingService();
        $createEasyPostShipment         = $easyPostShipmentMappingService->handleMapping($shipment);

        $easyPostShipmentRepo->rate($createEasyPostShipment);
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
        $orders                         = $createShipmentsService->getPendingFulfillmentOrders();

        foreach ($orders AS $order)
        {
            $job                            = (new \App\Jobs\Shipments\CreateShipmentsJob($order->getId(), 1))->onQueue('orderShipments');
            $this->dispatch($job);
        }

        return response ('', 200);
    }


    /**
     * @param   int         $id
     * @param   string      $fieldName
     * @return  Shipment
     */
    private function getShipment ($id, $fieldName = 'id')
    {
        if (is_null($id))
            throw new BadRequestHttpException($fieldName . ' is required');
        else if (is_null(InputUtil::getInt($id)))
            throw new BadRequestHttpException($fieldName . ' must be integer');

        $shipmentValidation             = new ShipmentValidation();

        $shipment                       = $shipmentValidation->idExists($id);
        return $shipment;
    }
}