<?php

namespace App\Models\Shipments\Validation;


use App\Models\Shipments\Service;
use App\Repositories\Doctrine\Shipments\ServiceRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceValidation
{

    /**
     * @var ServiceRepository
     */
    private $serviceRepo;


    public function __construct()
    {
        $this->serviceRepo                  = EntityManager::getRepository('App\Models\Shipments\Service');
    }

    /**
     * @param   int     $id
     * @return  Service|null
     */
    public function idExists ($id)
    {
        $service                            = $this->serviceRepo->getOneById($id);

        if (is_null($service))
            throw new NotFoundHttpException('Service not found');

        return $service;
    }
}