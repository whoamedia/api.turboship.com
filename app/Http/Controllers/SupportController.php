<?php

namespace App\Http\Controllers;


use App\Http\Requests\Support\GetOrderStatuses;
use App\Http\Requests\Support\GetPrinterTypes;
use App\Http\Requests\Support\GetShipmentStatuses;
use App\Http\Requests\Support\GetShippingContainerTypes;
use App\Http\Requests\Support\GetSources;
use App\Http\Requests\Support\GetSubdivisionTypes;
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
     * @var \App\Repositories\Doctrine\Hardware\PrinterTypeRepository
     */
    private $printerTypeRepo;

    /**
     * @var \App\Repositories\Doctrine\Support\ShipmentStatusRepository
     */
    private $shipmentStatusRepo;

    /**
     * @var \App\Repositories\Doctrine\Support\ShippingContainerTypeRepository
     */
    private $shippingContainerTypeRepo;

    /**
     * @var \App\Repositories\Doctrine\Locations\SubdivisionTypeRepository
     */
    private $subdivisionTypeRepo;


    public function getSources (Request $request)
    {
        $getSources                     = new GetSources($request->input());
        $getSources->validate();
        $getSources->clean();

        $query                          = $getSources->jsonSerialize();
        $this->sourceRepo               = EntityManager::getRepository('App\Models\Support\Source');

        return $this->sourceRepo->where($query, false);
    }

    public function getOrderStatuses (Request $request)
    {
        $getOrderStatuses               = new GetOrderStatuses($request->input());
        $getOrderStatuses->validate();
        $getOrderStatuses->clean();

        $query                          = $getOrderStatuses->jsonSerialize();
        $this->orderStatusRepo          = EntityManager::getRepository('App\Models\OMS\OrderStatus');

        return $this->orderStatusRepo->where($query, false);
    }

    public function getShipmentStatuses (Request $request)
    {
        $getShipmentStatuses            = new GetShipmentStatuses($request->input());
        $getShipmentStatuses->validate();
        $getShipmentStatuses->clean();

        $query                          = $getShipmentStatuses->jsonSerialize();
        $this->shipmentStatusRepo       = EntityManager::getRepository('App\Models\Support\ShipmentStatus');

        return $this->shipmentStatusRepo->where($query, false);
    }

    public function getSubdivisionTypes (Request $request)
    {
        $getSubdivisionTypes            = new GetSubdivisionTypes($request->input());
        $getSubdivisionTypes->validate();
        $getSubdivisionTypes->clean();

        $query                          = $getSubdivisionTypes->jsonSerialize();
        $this->subdivisionTypeRepo      = EntityManager::getRepository('App\Models\Locations\SubdivisionType');

        return $this->subdivisionTypeRepo->where($query, false);
    }

    public function getShippingContainerTypes (Request $request)
    {
        $getShippingContainerTypes      = new GetShippingContainerTypes($request->input());
        $getShippingContainerTypes->validate();
        $getShippingContainerTypes->clean();

        $query                          = $getShippingContainerTypes->jsonSerialize();
        $this->shippingContainerTypeRepo= EntityManager::getRepository('App\Models\Support\ShippingContainerType');

        return $this->shippingContainerTypeRepo->where($query, false);
    }

    public function getGetPrinterTypes (Request $request)
    {
        $getPrinterTypes                = new GetPrinterTypes($request->input());
        $getPrinterTypes->validate();
        $getPrinterTypes->clean();

        $query                          = $getPrinterTypes->jsonSerialize();
        $this->printerTypeRepo          = EntityManager::getRepository('App\Models\Hardware\PrinterType');

        return $this->printerTypeRepo->where($query, false);
    }
}