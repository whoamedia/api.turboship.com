<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\IntegratedWebHook;
use App\Repositories\Doctrine\Integrations\IntegratedWebHookRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegratedWebHookValidation
{

    /**
     * @var IntegratedWebHookRepository
     */
    private $integratedWebHookRepo;


    public function __construct()
    {
        $this->integratedWebHookRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedWebHook');
    }

    /**
     * @param   int     $id
     * @return  IntegratedWebHook
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $integratedWebHook              = $this->integratedWebHookRepo->getOneById($id);

        if (is_null($integratedWebHook))
            throw new NotFoundHttpException('IntegratedWebHook not found');

        return $integratedWebHook;
    }

}