<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\ClientIntegration;
use App\Repositories\Doctrine\Integrations\ClientIntegrationRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientIntegrationValidation
{

    /**
     * @var ClientIntegrationRepository
     */
    private $clientIntegrationRepo;


    public function __construct()
    {
        $this->clientIntegrationRepo    = EntityManager::getRepository('App\Models\Integrations\ClientIntegration');
    }


    /**
     * @param   int     $id
     * @return  ClientIntegration
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $clientIntegration              = $this->clientIntegrationRepo->getOneById($id);

        if (is_null($clientIntegration))
            throw new NotFoundHttpException('ClientIntegration not found');

        return $clientIntegration;
    }

}