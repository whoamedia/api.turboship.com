<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shippers\AddClientToShipper;
use App\Http\Requests\Shippers\GetShippers;
use App\Http\Requests\Shippers\ShowShipper;
use App\Http\Requests\Shippers\UpdateShipper;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Shipments\Shipper;
use App\Models\Shipments\Validation\ShipperValidation;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShipperController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\Shipments\ShipperRepository
     */
    private $shipperRepo;


    /**
     * ClientController constructor.
     */
    public function __construct ()
    {
        $this->shipperRepo              = EntityManager::getRepository('App\Models\Shipments\Shipper');
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

        $results                        = $this->shipperRepo->where($query, false);
        return response($results);
    }


    public function show (Request $request)
    {
        $shipper                        = $this->getShipperFromRoute($request->route('id'));
        return response($shipper);
    }

    public function update (Request $request)
    {
        $updateShipper                  = new UpdateShipper($request->input());
        $updateShipper->setId($request->route('id'));
        $updateShipper->validate();
        $updateShipper->clean();

        $shipper                        = $this->getShipperFromRoute($updateShipper->getId());

        if (!is_null($updateShipper->getName()))
            $shipper->setName($updateShipper->getName());

        $this->shipperRepo->saveAndCommit($shipper);
        return response($shipper);
    }

    public function showAddress (Request $request)
    {
        $shipper                        = $this->getShipperFromRoute($request->route('id'));
        return response($shipper->getAddress());
    }

    public function updateAddress (Request $request)
    {
        $shipper                        = $this->getShipperFromRoute($request->route('id'));

    }

    public function updateReturnAddress (Request $request)
    {
        $shipper                        = $this->getShipperFromRoute($request->route('id'));

    }

    public function showReturnAddress (Request $request)
    {
        $shipper                        = $this->getShipperFromRoute($request->route('id'));
        return response($shipper->getReturnAddress());
    }

    public function getShippingApis (Request $request)
    {
        $shipper                        = $this->getShipperFromRoute($request->route('id'));
        return response($shipper->getIntegratedShippingApis());
    }

    public function getClients (Request $request)
    {
        $shipper                        = $this->getShipperFromRoute($request->route('id'));
        return response($shipper->getClients());
    }

    public function addClient (Request $request)
    {
        $addClientToShipper             = new AddClientToShipper();
        $addClientToShipper->setId($request->route('id'));
        $addClientToShipper->setClientId($request->route('clientId'));

        $clientValidation               = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));
        $shipper                        = $this->getShipperFromRoute($addClientToShipper->getId());
        $client                         = $clientValidation->idExists($addClientToShipper->getClientId());

        if ($shipper->hasClient($client))
            throw new BadRequestHttpException('Client already has permissions to Shipper');

        $shipper->addClient($client);
        $this->shipperRepo->saveAndCommit($client);

        return response($shipper->getClients(), 201);
    }

    public function removeClient (Request $request)
    {
        $addClientToShipper             = new AddClientToShipper();
        $addClientToShipper->setId($request->route('id'));
        $addClientToShipper->setClientId($request->route('clientId'));

        $clientValidation               = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));
        $shipper                        = $this->getShipperFromRoute($addClientToShipper->getId());
        $client                         = $clientValidation->idExists($addClientToShipper->getClientId());

        if (!$shipper->hasClient($client))
            throw new BadRequestHttpException('Client does not have permissions to Shipper');

        $shipper->removeClient($client);
        $this->shipperRepo->saveAndCommit($client);

        return response($shipper->getClients(), 204);
    }

    /**
     * @param   int     $id
     * @return  Shipper
     */
    private function getShipperFromRoute ($id)
    {
        $showShipper                    = new ShowShipper();
        $showShipper->setId($id);
        $showShipper->validate();
        $showShipper->clean();

        $shipperValidation              = new ShipperValidation();
        $shipper                        = $shipperValidation->idExists($showShipper->getId());
        return $shipper;
    }
}