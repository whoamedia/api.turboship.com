<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shippers\AddClientToShipper;
use App\Http\Requests\Shippers\GetShippers;
use App\Http\Requests\Shippers\ShowShipper;
use App\Models\CMS\Validation\ClientValidation;
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
        $shipper                        = $this->getOneById($request->route('id'));
        return response($shipper);
    }

    public function showAddress (Request $request)
    {
        $shipper                        = $this->getOneById($request->route('id'));
        return response($shipper->getAddress());
    }

    public function showReturnAddress (Request $request)
    {
        $shipper                        = $this->getOneById($request->route('id'));
        return response($shipper->getReturnAddress());
    }

    public function getClients (Request $request)
    {
        $shipper                        = $this->getOneById($request->route('id'));
        return response($shipper->getClients());
    }

    public function addClient (Request $request)
    {
        $addClientToShipper             = new AddClientToShipper();
        $addClientToShipper->setId($request->route('id'));
        $addClientToShipper->setClientId($request->route('clientId'));

        $clientValidation               = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));
        $shipper                        = $this->getOneById($addClientToShipper->getId());
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
        $shipper                        = $this->getOneById($addClientToShipper->getId());
        $client                         = $clientValidation->idExists($addClientToShipper->getClientId());

        if (!$shipper->hasClient($client))
            throw new BadRequestHttpException('Client does not have permissions to Shipper');

        $shipper->removeClient($client);
        $this->shipperRepo->saveAndCommit($client);

        return response($shipper->getClients(), 204);
    }

    /**
     * @param $id
     * @return \App\Models\Shipments\Shipper
     */
    private function getOneById ($id)
    {
        $showShipper                    = new ShowShipper();
        $showShipper->setId($id);
        $showShipper->validate();
        $showShipper->clean();

        $shipper                        = $this->shipperValidation->idExists($showShipper->getId());
        return $shipper;
    }
}