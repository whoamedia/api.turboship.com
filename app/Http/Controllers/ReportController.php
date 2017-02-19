<?php

namespace App\Http\Controllers;


use App\Http\Responses\WarehouseActivityReport;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Utilities\ShipmentStatusUtility;
use Illuminate\Http\Request;
use EntityManager;

class ReportController extends BaseAuthController
{

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;


    public function __construct()
    {
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
    }

    public function getWarehouseActivityReport (Request $request)
    {
        $staff                          = parent::getAuthStaff();
        $warehouseActivityReport        = new WarehouseActivityReport();

        $pendingQuery   = [
            'organizationIds'           => $staff->getOrganization()->getId(),
            'statusIds'                 => ShipmentStatusUtility::PENDING
        ];

        $pendingResults                 = $this->shipmentRepo->where($pendingQuery);
        $warehouseActivityReport->setPendingShipments(sizeof($pendingResults));

        return response($warehouseActivityReport);
    }

}