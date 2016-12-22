<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\IntegrationCredential;
use App\Repositories\Doctrine\Integrations\IntegrationCredentialRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegrationCredentialValidation
{

    /**
     * @var IntegrationCredentialRepository
     */
    private $integrationCredentialRepo;


    public function __construct()
    {
        $this->integrationCredentialRepo    = EntityManager::getRepository('App\Models\Integrations\IntegrationCredential');
    }

    /**
     * @param   int     $id
     * @return  IntegrationCredential
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $integrationCredential              = $this->integrationCredentialRepo->getOneById($id);

        if (is_null($integrationCredential))
            throw new NotFoundHttpException('IntegrationCredential not found');

        return $integrationCredential;
    }

}