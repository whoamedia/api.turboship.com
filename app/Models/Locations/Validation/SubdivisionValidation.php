<?php

namespace App\Models\Locations\Validation;


use App\Models\Locations\Subdivision;
use App\Repositories\Doctrine\Locations\SubdivisionRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubdivisionValidation
{

    /**
     * @var SubdivisionRepository
     */
    private $subdivisionRepo;


    public function __construct(SubdivisionRepository $subdivisionRepo)
    {
        $this->subdivisionRepo          = $subdivisionRepo;
    }

    /**
     * @param   int     $id
     * @return  Subdivision
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $entity                     = $this->subdivisionRepo->getOneById($id);
        if (is_null($entity))
            throw new NotFoundHttpException('Subdivision id does not exist');
        return $entity;
    }
}