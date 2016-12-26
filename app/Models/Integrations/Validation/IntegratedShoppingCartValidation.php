<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\IntegratedShoppingCart;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegratedShoppingCartValidation
{

    /**
     * @var IntegratedShoppingCartRepository
     */
    private $integratedShoppingCartRepo;


    public function __construct()
    {
        $this->integratedShoppingCartRepo   = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
    }

    /**
     * @param   int     $id
     * @return  IntegratedShoppingCart
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $integratedShoppingCart             = $this->integratedShoppingCartRepo->getOneById($id);

        if (is_null($integratedShoppingCart))
            throw new NotFoundHttpException('IntegratedShoppingCart not found');

        return $integratedShoppingCart;
    }

}