<?php

namespace App\Models\CMS\Validation;


use App\Repositories\Doctrine\CMS\ClientRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\CMS\Client;
use EntityManager;

class ClientValidation
{

    /**
     * @var ClientRepository
     */
    private $clientRepo;


    /**
     * ClientValidation constructor.
     * @param ClientRepository|null $clientRepo
     */
    public function __construct($clientRepo = null)
    {
        if (is_null($clientRepo))
            $this->clientRepo           = EntityManager::getRepository('App\Models\CMS\Client');
        else
            $this->clientRepo           = $clientRepo;
    }


    /**
     * @param   int     $id
     * @param   bool    $validateOrganization       Validate that the provided user belongs to the same organization as the authUser
     * @return  Client
     * @throws  NotFoundHttpException
     */
    public function idExists($id, $validateOrganization = false)
    {
        $client                         = $this->clientRepo->getOneById($id);

        if (is_null($client))
            throw new NotFoundHttpException('Client not found');

        if ($validateOrganization)
        {
            if (\Auth::getUser()->getOrganization()->getId() != $client->getOrganization()->getId())
                throw new NotFoundHttpException('Client not found');
        }

        return $client;
    }

}