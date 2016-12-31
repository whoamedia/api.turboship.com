<?php

namespace App\Models\Shipments\Validation;


use App\Models\Shipments\Rate;
use App\Repositories\Doctrine\Shipments\RateRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RateValidation
{

    /**
     * @var RateRepository
     */
    protected $rateRepo;


    public function __construct()
    {
        $this->rateRepo                     = EntityManager::getRepository('App\Models\Shipments\Rate');
    }

    /**
     * @param   int     $id
     * @return  Rate
     */
    public function idExists ($id)
    {
        $rate                               = $this->rateRepo->getOneById($id);
        if (is_null($rate))
            throw new NotFoundHttpException('Rate not found');

        return $rate;
    }
}