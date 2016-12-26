<?php

namespace App\Http\Controllers;


use App\Repositories\Doctrine\Integrations\IntegratedServiceRepository;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use EntityManager;

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

}