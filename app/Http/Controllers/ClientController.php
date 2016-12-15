<?php

namespace App\Http\Controllers;


use App\Http\Requests\Clients\CreateClientRequest;
use App\Http\Requests\Clients\GetClientsRequest;
use App\Http\Requests\Clients\ShowClientRequest;
use App\Http\Requests\Clients\UpdateClientRequest;
use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ClientController extends Controller
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
        $authUser                       = \Auth::getUser();

        $getClientsRequest              = new GetClientsRequest($request->input());
        $getClientsRequest->setOrganizationIds($authUser->getOrganization()->getId());
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
                $this->clientValidation->uniqueOrganizationAndName($client->getOrganization(), $updateClientRequest->getName());
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

        $authUser                       = \Auth::getUser();

        $client                         = new Client();
        $client->setName($createClientRequest->getName());
        $client->setOrganization($authUser->getOrganization());
        $client->validate();

        //  TODO: Move this to Client Model
        $this->clientValidation->uniqueOrganizationAndName($client->getOrganization(), $client->getName());

        $this->clientRepo->saveAndCommit($client);

        return response($client, 201);
    }


}