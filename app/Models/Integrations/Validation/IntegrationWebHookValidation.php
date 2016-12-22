<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\IntegrationWebHook;
use App\Repositories\Doctrine\Integrations\IntegrationWebHookRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegrationWebHookValidation
{

    /**
     * @var IntegrationWebHookRepository
     */
    private $integrationWebHookRepo;


    public function __construct()
    {
        $this->integrationWebHookRepo   = EntityManager::getRepository('App\Models\Integrations\IntegrationWebHook');
    }

    /**
     * @param   int     $id
     * @return  IntegrationWebHook
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $integrationWebHook             = $this->integrationWebHookRepo->getOneById($id);

        if (is_null($integrationWebHook))
            throw new NotFoundHttpException('IntegrationWebHook not found');

        return $integrationWebHook;
    }

}