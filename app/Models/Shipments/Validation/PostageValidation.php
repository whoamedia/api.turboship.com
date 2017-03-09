<?php

namespace App\Models\Shipments\Validation;


use App\Models\Shipments\Postage;
use App\Repositories\Doctrine\Shipments\PostageRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostageValidation
{

    /**
     * @var PostageRepository
     */
    private $postageRepo;


    public function __construct()
    {
        $this->postageRepo                  = EntityManager::getRepository('App\Models\Shipments\Postage');
    }

    /**
     * @param   int     $id
     * @return  Postage|null
     */
    public function idExists ($id)
    {
        $postage                            = $this->postageRepo->getOneById($id);

        if (is_null($postage))
            throw new NotFoundHttpException('Postage not found');

        return $postage;
    }

}