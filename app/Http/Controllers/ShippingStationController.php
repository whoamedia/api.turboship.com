<?php

namespace App\Http\Controllers;


use App\Http\Requests\ShippingStations\CreateShippingStation;
use App\Http\Requests\ShippingStations\GetShippingStations;
use App\Http\Requests\ShippingStations\PrintShipmentPostageLabel;
use App\Http\Requests\ShippingStations\ShowShippingStation;
use App\Http\Requests\ShippingStations\UpdateShippingStation;
use App\Models\Hardware\ShippingStation;
use App\Models\Hardware\Validation\PrinterValidation;
use App\Models\Hardware\Validation\ShippingStationValidation;
use App\Models\Shipments\Validation\ShipmentValidation;
use App\Repositories\Doctrine\Hardware\ShippingStationRepository;
use App\Services\PrinterService;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShippingStationController extends BaseAuthController
{

    /**
     * @var ShippingStationRepository
     */
    private $shippingStationRepo;

    /**
     * @var ShippingStationValidation
     */
    private $shippingStationValidation;


    public function __construct ()
    {
        $this->shippingStationRepo      = EntityManager::getRepository('App\Models\Hardware\ShippingStation');
        $this->shippingStationValidation= new ShippingStationValidation($this->shippingStationRepo);
    }


    public function index (Request $request)
    {
        $getShippingStations            = new GetShippingStations($request->input());
        $getShippingStations->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getShippingStations->validate();
        $getShippingStations->clean();

        $query                          = $getShippingStations->jsonSerialize();
        $results                        = $this->shippingStationRepo->where($query, false);
        return response($results);
    }

    public function show (Request $request)
    {
        $showShippingStation            = new ShowShippingStation();
        $showShippingStation->setId($request->route('id'));
        $showShippingStation->validate();
        $showShippingStation->clean();

        $shippingStation                = $this->shippingStationValidation->idExists($showShippingStation->getId());
        return response($shippingStation);
    }


    public function create (Request $request)
    {
        $createShippingStation          = new CreateShippingStation($request->input());
        $createShippingStation->setOrganizationId(parent::getAuthUserOrganization()->getId());
        $createShippingStation->validate();
        $createShippingStation->clean();

        $shippingStation                = new ShippingStation();
        $shippingStation->setOrganization(parent::getAuthUserOrganization());
        $shippingStation->setName($createShippingStation->getName());

        $printerValidation              = new PrinterValidation();
        $printer                        = $printerValidation->idExists($createShippingStation->getPrinterId());
        $shippingStation->setPrinter($printer);

        $this->shippingStationRepo->saveAndCommit($shippingStation);
        return response($shippingStation, 201);
    }

    public function update (Request $request)
    {
        $updateShippingStation          = new UpdateShippingStation($request->input());
        $updateShippingStation->setId($request->route('id'));
        $updateShippingStation->validate();
        $updateShippingStation->clean();

        $shippingStation                = $this->shippingStationValidation->idExists($updateShippingStation->getId());

        if (!is_null($updateShippingStation->getName()))
            $shippingStation->setName($updateShippingStation->getName());

        if (!is_null($updateShippingStation->getPrinterId()))
        {
            $printerValidation          = new PrinterValidation();
            $printer                    = $printerValidation->idExists($updateShippingStation->getPrinterId());
            $shippingStation->setPrinter($printer);
        }

        $this->shippingStationRepo->saveAndCommit($shippingStation);
        return response($shippingStation);
    }


    public function printShipmentPostageLabel (Request $request)
    {
        $printShipmentPostageLabel      = new PrintShipmentPostageLabel($request->input());
        $printShipmentPostageLabel->setId($request->route('id'));
        $printShipmentPostageLabel->setShipmentId($request->route('shipmentId'));
        $printShipmentPostageLabel->validate();
        $printShipmentPostageLabel->clean();

        $shippingStation                = $this->shippingStationValidation->idExists($printShipmentPostageLabel->getId());

        $shipmentValidation             = new ShipmentValidation();
        $shipment                       = $shipmentValidation->idExists($printShipmentPostageLabel->getShipmentId());
        if (is_null($shipment->getPostage()))
            throw new BadRequestHttpException('Shipment has no postage to print');

        $printerService                 = new PrinterService();
        $printerService->printLabel($shipment->getPostage(), $shippingStation->getPrinter());
        return response('');
    }

}