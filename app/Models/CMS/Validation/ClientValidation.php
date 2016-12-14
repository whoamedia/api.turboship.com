<?php

namespace App\Models\CMS\Validation;


use App\Models\CMS\Organization;
use App\Repositories\Doctrine\CMS\ClientRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\CMS\Client;

class ClientValidation
{

    /**
     * @var ClientRepository
     */
    private $clientRepo;


    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepo               = $clientRepo;
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


    public function uniqueOrganizationAndName (Organization $organization, $name)
    {
        $query      = [
            'organizationIds'           => $organization->getId(),
            'names'                     => $name
        ];

        $result                         = $this->clientRepo->where($query);
        if (sizeof($result) != 0)
            throw new BadRequestHttpException('Client name already exists');
    }

}