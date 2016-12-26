<?php

namespace App\Http\Controllers;


use App\Repositories\Doctrine\Integrations\IntegratedServiceRepository;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use EntityManager;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseIntegratedServiceController extends BaseAuthController
{

    /**
     * @var IntegratedServiceRepository
     */
    protected $integratedServiceRepo;

    /**
     * @var IntegratedShippingApiRepository
     */
    protected $integratedShippingApiRepo;

    /**
     * @var IntegratedShoppingCartRepository
     */
    protected $integratedShoppingCartRepo;


    public function __construct()
    {
        $this->integratedServiceRepo        = EntityManager::getRepository('App\Models\Integrations\IntegratedService');
        $this->integratedShippingApiRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedShippingApi');
        $this->integratedShoppingCartRepo   = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
    }

    /**
     * @param   int $id
     * @return  \App\Models\Integrations\IntegratedShoppingCart|null
     */
    protected function getIntegratedShoppingCart ($id)
    {
        if (is_null($id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($id)))
            throw new BadRequestHttpException('id must be integer');

        $integratedShoppingCart             = $this->integratedShoppingCartRepo->getOneById($id);
        if (is_null($integratedShoppingCart))
            throw new NotFoundHttpException('IntegratedShoppingCart not found');

        return $integratedShoppingCart;
    }
}