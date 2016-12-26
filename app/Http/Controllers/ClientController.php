<?php

namespace App\Http\Controllers;


use App\Http\Requests\Clients\CreateClientRequest;
use App\Http\Requests\Clients\CreateClientServices;
use App\Http\Requests\Clients\DeleteClientService;
use App\Http\Requests\Clients\GetClientsRequest;
use App\Http\Requests\Clients\ShowClientRequest;
use App\Http\Requests\Clients\UpdateClientRequest;
use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Shipments\Validation\ServiceValidation;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $getClientsRequest              = new GetClientsRequest($request->input());
        $getClientsRequest->setOrganizationIds($this->getAuthUserOrganization()->getId());

        if (!is_null(\Auth::getUser()->getClient()))
            $getClientsRequest->setIds(\Auth::getUser()->getClient()->getId());

        $getClientsRequest->validate();
        $getClientsRequest->clean();

        $query                          = $getClientsRequest->jsonSerialize();

        $results                        = $this->clientRepo->where($query, false);
        return response($results);
    }

    /**
     * @param   Request $request
     * @return  Client
     */
    public function show (Request $request)
    {
        $showClientRequest              = new ShowClientRequest();
        $showClientRequest->setId($request->route('id'));
        $showClientRequest->validate();
        $showClientRequest->clean();

        $client                         = $this->clientValidation->idExists($showClientRequest->getId(), true);

        return response($client);
    }

    /**
     * @param   Request $request
     * @return  Client
     */
    public function update (Request $request)
    {
        $updateClientRequest            = new UpdateClientRequest($request->input());
        $updateClientRequest->setId($request->route('id'));
        $updateClientRequest->validate();
        $updateClientRequest->clean();

        $client                         = $this->clientValidation->idExists($updateClientRequest->getId(), true);

        if ($client->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new AccessDeniedHttpException('Insufficient permissions to update clients that belong to other organizations');


        if (!is_null($updateClientRequest->getName()))
        {
            if ($updateClientRequest->getName() != $client->getName())
            {
                $client->setName($updateClientRequest->getName());
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
        $createClientRequest            = new CreateClientRequest($request->input());
        $createClientRequest->validate();

        $client                         = new Client();
        $client->setName($createClientRequest->getName());
        $client->setOrganization($this->getAuthUserOrganization());
        $client->validate();

        $this->clientRepo->saveAndCommit($client);

        return response($client, 201);
    }

    public function getServices (Request $request)
    {
        $showClientRequest              = new ShowClientRequest();
        $showClientRequest->setId($request->route('id'));
        $showClientRequest->validate();
        $showClientRequest->clean();

        $client                         = $this->clientValidation->idExists($showClientRequest->getId(), true);

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
}