<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shipments\GetPostage;
use App\Http\Requests\Shipments\PurchasePostage;
use App\Http\Requests\Shipments\RateShipment;
use App\Http\Requests\Shipments\GetShipments;
use App\Http\Requests\Shipments\UpdateShipment;
use App\Http\Requests\Shipments\VoidPostage;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Shipments\Shipment;
use App\Models\Shipments\Validation\RateValidation;
use App\Models\Shipments\Validation\ServiceValidation;
use App\Models\Shipments\Validation\ShipmentValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Models\Support\Validation\ShipmentStatusValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\Shipments\PostageService;
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
        $this->clientValidation         = new ClientValidation();
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

    public function getLexicon (Request $request)
    {
        $getShipments                   = new GetShipments($request->input());
        $getShipments->setOrganizationIds($this->getAuthUserOrganization()->getId());
        $getShipments->validate();
        $getShipments->clean();

        $query                          = $getShipments->jsonSerialize();
        $shipments                      = $this->shipmentRepo->getLexicon($query);
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

        if (!is_null($updateShipment->getServiceId()))
        {
            $serviceValidation          = new ServiceValidation();
            $service                    = $serviceValidation->idExists($updateShipment->getServiceId());
            $shipment->setService($service);
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

        $postageService                 = new PostageService($integratedShippingApi);
        $postageService->rate($shipment);

        $this->shipmentRepo->saveAndCommit($shipment);


        return response($shipment->getRates(), 201);
    }

    public function getPostage (Request $request)
    {
        $getPostage                     = new GetPostage();
        $getPostage->setId($request->route('id'));
        $getPostage->validate();
        $getPostage->clean();

        $shipment                       = $this->getShipment($getPostage->getId());

        if (is_null($shipment->getPostage()))
            throw new NotFoundHttpException('Shipment has not postage');

        return response($shipment->getPostage());
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

        $postageService                 = new PostageService($rate->getIntegratedShippingApi());
        $postageService->purchase($shipment, $rate);
        $this->shipmentRepo->saveAndCommit($shipment);

        $postageService->handleOrderShippedLogic($shipment);

        return response ($shipment->getPostage(), 201);
    }

    public function voidPostage (Request $request)
    {
        $voidPostage                    = new VoidPostage();
        $voidPostage->setId($request->route('id'));
        $voidPostage->validate();
        $voidPostage->clean();

        $shipment                       = $this->getShipment($voidPostage->getId());
        $shipment->canVoidPostage();

        $postageService                 = new PostageService($shipment->getPostage()->getIntegratedShippingApi());
        $postageService->void($shipment);
        $this->shipmentRepo->saveAndCommit($shipment);

        $postageService->handleOrderVoidedLogic($shipment);

        return response('', 204);
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