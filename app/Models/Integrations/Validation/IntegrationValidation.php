<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\Integration;
use App\Repositories\Doctrine\Integrations\IntegrationRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegrationValidation
{

    /**
     * @var IntegrationRepository
     */
    private $integrationRepo;


    public function __construct()
    {
        $this->integrationRepo          = EntityManager::getRepository('App\Models\Integrations\Integration');
    }

    /**
     * @param   int     $id
     * @return  Integration
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $integration                    = $this->integrationRepo->getOneById($id);

        if (is_null($integration))
            throw new NotFoundHttpException('Integration not found');

        return $integration;
    }

}