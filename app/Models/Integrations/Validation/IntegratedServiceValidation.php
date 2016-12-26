<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\IntegratedService;
use App\Repositories\Doctrine\Integrations\IntegratedServiceRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegratedServiceValidation
{

    /**
     * @var IntegratedServiceRepository
     */
    private $integratedServiceRepo;


    public function __construct()
    {
        $this->integratedServiceRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedService');
    }


    /**
     * @param   int     $id
     * @return  IntegratedService
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $integratedService              = $this->integratedServiceRepo->getOneById($id);

        if (is_null($integratedService))
            throw new NotFoundHttpException('IntegratedService not found');

        return $integratedService;
    }

}