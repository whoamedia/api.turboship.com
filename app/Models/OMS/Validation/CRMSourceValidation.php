<?php

namespace App\Models\OMS\Validation;

use App\Models\OMS\CRMSource;
use App\Repositories\Doctrine\OMS\CRMSourceRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CRMSourceValidation
{

    /**
     * @var CRMSourceRepository
     */
    private $crmSourceRepo;


    public function __construct()
    {
        $this->crmSourceRepo                = EntityManager::getRepository('App\Models\OMS\CRMSource');
    }

    /**
     * @param   int     $id
     * @return  CRMSource
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $crmSource                         = $this->crmSourceRepo->getOneById($id);

        if (is_null($crmSource))
            throw new NotFoundHttpException('CRMSource not found');

        return $crmSource;
    }

}