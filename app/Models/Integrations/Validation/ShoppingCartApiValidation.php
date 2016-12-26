<?php

namespace App\Models\Integrations\Validation;


use App\Models\Integrations\ShoppingCartIntegration;
use App\Repositories\Doctrine\Integrations\ShoppingCartIntegrationRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShoppingCartApiValidation
{

    /**
     * @var ShoppingCartIntegrationRepository
     */
    private $shoppingCartIntegrationRepo;


    public function __construct()
    {
        $this->shoppingCartIntegrationRepo=EntityManager::getRepository('App\Models\Integrations\ShoppingCartIntegration');
    }

    /**
     * @param   int     $id
     * @return  ShoppingCartIntegration
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $shoppingCartIntegration            = $this->shoppingCartIntegrationRepo->getOneById($id);

        if (is_null($shoppingCartIntegration))
            throw new NotFoundHttpException('ShoppingCartIntegration not found');

        return $shoppingCartIntegration;
    }

}