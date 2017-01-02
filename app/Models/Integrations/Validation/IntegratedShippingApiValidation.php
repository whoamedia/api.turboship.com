<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\IntegratedShippingApi;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegratedShippingApiValidation
{

    /**
     * @var IntegratedShippingApiRepository
     */
    protected $integratedShippingApiRepo;


    public function __construct()
    {
        $this->integratedShippingApiRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedShippingApi');
    }

    /**
     * @param   int     $id
     * @return  IntegratedShippingApi
     */
    public function idExists ($id)
    {
        $integratedShippingApi              = $this->integratedShippingApiRepo->getOneById($id);

        if (is_null($integratedShippingApi))
            throw new NotFoundHttpException('IntegratedShippingApi does not exist');

        return $integratedShippingApi;
    }

}