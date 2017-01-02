<?php

namespace App\Http\Controllers;


use App\Http\Requests\Clients\CreateClient;
use App\Http\Requests\Clients\CreateClientServices;
use App\Http\Requests\Clients\DeleteClientService;
use App\Http\Requests\Clients\GetClients;
use App\Http\Requests\Clients\ShowClient;
use App\Http\Requests\Clients\UpdateClientOptions;
use App\Http\Requests\Clients\UpdateClient;
use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Integrations\Validation\IntegratedShippingApiValidation;
use App\Models\Shipments\Validation\ServiceValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ClientController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\CMS\ClientRepository
     */
    private $clientRepo;

    /**
     * @var ClientValidation
     */
    private $clientValidation;


    /**
     * ClientController constructor.
     */
    public function __construct ()
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->clientValidation         = new ClientValidation($this->clientRepo);
    }


    /**
     * @param   Request $request
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index (Request $request)
    {
        $getClients                     = new GetClients($request->input());
        $getClients->setOrganizationIds($this->getAuthUserOrganization()->getId());

        if (!is_null(parent::getAuthUser()->getClient()))
            $getClients->setIds(parent::getAuthUserOrganization()->getId());

        $getClients->validate();
        $getClients->clean();

        $query                          = $getClients->jsonSerialize();

        $results                        = $this->clientRepo->where($query, false);
        return response($results);
    }

    /**
     * @param   Request $request
     * @return  Client
     */
    public function show (Request $request)
    {
        $client                         = $this->getClientFromRoute($request->route('id'));
        return response($client);
    }

    /**
     * @param   Request $request
     * @return  Client
     */
    public function update (Request $request)
    {
        $updateClient                   = new UpdateClient($request->input());
        $updateClient->setId($request->route('id'));
        $updateClient->validate();
        $updateClient->clean();

        $client                         = $this->getClientFromRoute($updateClient->getId());

        if ($client->getOrganization()->getId() != parent::getAuthUserOrganization()->getId())
            throw new AccessDeniedHttpException('Insufficient permissions to update clients that belong to other organizations');


        if (!is_null($updateClient->getName()))
        {
            if ($updateClient->getName() != $client->getName())
            {
                $client->setName($updateClient->getName());
            }
        }

        $this->clientRepo->saveAndCommit($client);
        return response($client);
    }

    /**
     * @param   Request $request
     * @return  Client
     */
    public function store (Request $request)
    {
        $createClient                   = new CreateClient($request->input());
        $createClient->validate();

        $client                         = new Client();
        $client->setName($createClient->getName());
        $client->setOrganization($this->getAuthUserOrganization());
        $client->validate();

        $this->clientRepo->saveAndCommit($client);

        return response($client, 201);
    }

    public function getOptions (Request $request)
    {
        $client                         = $this->getClientFromRoute($request->route('id'));
        return response($client->getOptions());
    }


    public function updateOptions (Request $request)
    {
        $updateClientOptions            = new UpdateClientOptions($request->input());
        $updateClientOptions->setId($request->route('id'));
        $updateClientOptions->validate();
        $updateClientOptions->clean();

        $client                         = $this->getClientFromRoute($updateClientOptions->getId());

        if ($request->has('defaultShipToPhone'))
            $client->getOptions()->setDefaultShipToPhone($updateClientOptions->getDefaultShipToPhone());

        if ($request->has('defaultShipperId'))
        {
            if (is_null($updateClientOptions->getDefaultShipperId()))
                $client->getOptions()->setDefaultShipper(null);
            else
            {
                $shipperValidation      = new ShipperValidation();
                $shipper                = $shipperValidation->idExists($updateClientOptions->getDefaultShipperId());
                if (!$shipper->hasClient($client))
                    throw new BadRequestHttpException('Client does not have permissions to provided shipper');
                else
                    $client->getOptions()->setDefaultShipper($shipper);
            }
        }

        if ($request->has('defaultIntegratedShippingApiId'))
        {
            if (is_null($updateClientOptions->getDefaultIntegratedShippingApiId()))
                $client->getOptions()->setDefaultIntegratedShippingApi(null);
            else
            {
                $integratedShippingApiValidation    = new IntegratedShippingApiValidation();
                $integratedShippingApi              = $integratedShippingApiValidation->idExists($updateClientOptions->getDefaultIntegratedShippingApiId());

                if (!$integratedShippingApi->getShipper()->hasClient($client))
                    throw new BadRequestHttpException('Client does not have permissions to provided integratedShippingApi shipper');
                else
                    $client->getOptions()->setDefaultIntegratedShippingApi($integratedShippingApi);
            }
        }

        $this->clientRepo->saveAndCommit($client);
        return response($client->getOptions());
    }

    public function getServices (Request $request)
    {
        $client                         = $this->getClientFromRoute($request->route('id'));
        return response($client->getServices());
    }


    public function addService (Request $request)
    {
        $createClientServices           = new CreateClientServices();
        $createClientServices->setId($request->route('id'));
        $createClientServices->setServiceIds($request->input('serviceIds'));
        $createClientServices->validate();
        $createClientServices->clean();

        $client                         = $this->clientValidation->idExists($createClientServices->getId(), true);

        $serviceValidation              = new ServiceValidation();
        $serviceIds                     = explode(',', $createClientServices->getServiceIds());
        foreach ($serviceIds AS $id)
        {
            $service                    = $serviceValidation->idExists($id);
            if (!$client->hasService($service))
                $client->addService($service);
        }

        $this->clientRepo->saveAndCommit($client);

        return response($client->getServices());
    }


    public function removeService (Request $request)
    {
        $deleteClientService            = new DeleteClientService();
        $deleteClientService->setId($request->route('id'));
        $deleteClientService->setServiceId($request->route('serviceId'));
        $deleteClientService->validate();
        $deleteClientService->clean();

        $client                         = $this->clientValidation->idExists($deleteClientService->getId(), true);

        $serviceValidation              = new ServiceValidation();
        $service                        = $serviceValidation->idExists($deleteClientService->getServiceId());

        if ($client->hasService($service))
        {
            $client->removeService($service);
            $this->clientRepo->saveAndCommit($client);
        }

        return response('', 204);
    }

    /**
     * @param   int     $id
     * @return  Client
     */
    private function getClientFromRoute ($id)
    {
        $showClient                     = new ShowClient();
        $showClient->setId($id);
        $showClient->validate();
        $showClient->clean();

        $client                         = $this->clientValidation->idExists($showClient->getId());

        return $client;
    }
}