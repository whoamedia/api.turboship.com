<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shippers\GetShippers;
use App\Http\Requests\Shippers\ShowShipper;
use App\Models\Shipments\Validation\ShipperValidation;
use Illuminate\Http\Request;
use EntityManager;

class ShipperController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\Shipments\ShipperRepository
     */
    private $shipperRepo;

    /**
     * @var ShipperValidation
     */
    private $shipperValidation;


    /**
     * ClientController constructor.
     */
    public function __construct ()
    {
        $this->shipperRepo              = EntityManager::getRepository('App\Models\Shipments\Shipper');
        $this->shipperValidation        = new ShipperValidation();
    }


    public function index (Request $request)
    {
        $getShippers                    = new GetShippers($request->input());
        $getShippers->setOrganizationIds($this->getAuthUserOrganization()->getId());

        if ($this->authUserIsClient())
            $getShippers->setClientIds($this->getAuthUser()->getClient()->getId());

        $getShippers->validate();
        $getShippers->clean();

        $query                          = $getShippers->jsonSerialize();

        $results                        = $this->shipperRepo->where($query);
        return response($results);
    }


    public function show (Request $request)
    {
        $showShipper                    = new ShowShipper();
        $showShipper->setId($request->route('id'));
        $showShipper->validate();
        $showShipper->clean();

        $shipper                        = $this->shipperValidation->idExists($showShipper->getId());

        return response($shipper);
    }

    public function showAddress (Request $request)
    {
        $showShipper                    = new ShowShipper();
        $showShipper->setId($request->route('id'));
        $showShipper->validate();
        $showShipper->clean();

        $shipper                        = $this->shipperValidation->idExists($showShipper->getId());

        return response($shipper->getAddress());
    }

    public function showReturnAddress (Request $request)
    {
        $showShipper                    = new ShowShipper();
        $showShipper->setId($request->route('id'));
        $showShipper->validate();
        $showShipper->clean();

        $shipper                        = $this->shipperValidation->idExists($showShipper->getId());

        return response($shipper->getReturnAddress());
    }


}