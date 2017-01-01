<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shipments\PurchasePostage;
use App\Http\Requests\Shipments\RateShipment;
use App\Http\Requests\Shipments\CreateShipmentsJob;
use App\Http\Requests\Shipments\GetShipments;
use App\Http\Requests\Shipments\UpdateShipment;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Shipments\Shipment;
use App\Models\Shipments\Validation\RateValidation;
use App\Models\Shipments\Validation\ShipmentValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\Shipments\CreateShipmentsService;
use App\Services\Shipments\ShipmentRateService;
use Illuminate\Http\Request;
use EntityManager;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function update (Request $request)
    {
        $updateShipment                 = new UpdateShipment($request->input());
        $updateShipment->setId($request->route('id'));
        $updateShipment->validate();
        $updateShipment->clean();

        $shipment                       = $this->getShipment($updateShipment->getId());

        if (!is_null($updateShipment->getShippingContainerId()))
        {
            $shippingContainerValidation= new ShippingContainerValidation();
            $shippingContainer          = $shippingContainerValidation->idExists($updateShipment->getShippingContainerId());
            $shipment->setShippingContainer($shippingContainer);
        }

        if (!is_null($updateShipment->getWeight()))
        {
            $weight                     = $updateShipment->getWeight();
            $shipment->setWeight($weight);
        }

        $this->shipmentRepo->saveAndCommit($shipment);
        return response($shipment);
    }


    public function getRates (Request $request)
    {
        $shipment                       = $this->getShipment($request->route('id'));
        return response ($shipment->getRates());
    }

    public function createRates (Request $request)
    {
        $rateShipment                  = new RateShipment($request->input());
        $rateShipment->setId($request->route('id'));
        $rateShipment->validate();
        $rateShipment->clean();

        $shipment                       = $this->getShipment($rateShipment->getId());
        $integratedShippingApi          = $this->integratedShippingApiRepo->getOneById($rateShipment->getIntegratedShippingApiId());

        $shipmentRateService            = new ShipmentRateService($integratedShippingApi);
        $shipmentRateService->rate($shipment);

        $this->shipmentRepo->saveAndCommit($shipment);


        return response($shipment, 201);
    }

    public function purchasePostage (Request $request)
    {
        $purchasePostage                = new PurchasePostage();
        $purchasePostage->setId($request->route('id'));
        $purchasePostage->setRateId($request->route('rateId'));
        $purchasePostage->validate();
        $purchasePostage->clean();

        $shipment                       = $this->getShipment($purchasePostage->getId());

        $rateValidation                 = new RateValidation();
        $rate                           = $rateValidation->idExists($purchasePostage->getRateId());

        $shipmentRateService            = new ShipmentRateService($rate->getIntegratedShippingApi());
        $shipmentRateService->purchase($shipment, $rate);
        $this->shipmentRepo->saveAndCommit($shipment);
        return response ($shipment->getPostage(), 201);
    }

    public function voidPostage (Request $request)
    {
        $shipment                       = $this->getShipment($request->route('id'));
        if (is_null($shipment->getPostage()))
            throw new BadRequestHttpException('Shipment has no postage to void');


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


    public function getImages (Request $request)
    {
        $shipment                       = $this->getShipment($request->route('id'));
        return response($shipment->getImages());
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