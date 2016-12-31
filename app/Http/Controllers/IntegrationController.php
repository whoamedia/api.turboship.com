<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\GetIntegrationWebHooks;
use App\Http\Requests\Integrations\GetShippingApiIntegrations;
use App\Http\Requests\Integrations\GetShoppingCartIntegrations;
use App\Http\Requests\Integrations\ShowIntegration;
use App\Http\Requests\Integrations\ShowIntegrationCredentials;
use App\Models\Integrations\IntegrationWebHook;
use App\Models\Integrations\Validation\IntegrationValidation;
use App\Models\Integrations\Validation\ShippingApiIntegrationValidation;
use App\Repositories\Doctrine\Integrations\IntegrationRepository;
use App\Repositories\Doctrine\Integrations\ShippingApiIntegrationRepository;
use App\Repositories\Doctrine\Integrations\ShoppingCartIntegrationRepository;
use Illuminate\Http\Request;
use EntityManager;

class IntegrationController
{

    /**
     * @var IntegrationRepository
     */
    private $integrationRepo;

    /**
     * @var ShippingApiIntegrationRepository
     */
    private $shippingApiIntegrationRepo;

    /**
     * @var ShoppingCartIntegrationRepository
     */
    private $shoppingCartIntegrationRepo;

    /**
     * @var IntegrationValidation
     */
    private $integrationValidation;


    public function __construct()
    {
        $this->integrationRepo          = EntityManager::getRepository('App\Models\Integrations\Integration');
        $this->shippingApiIntegrationRepo=EntityManager::getRepository('App\Models\Integrations\ShippingApiIntegration');
        $this->shoppingCartIntegrationRepo=EntityManager::getRepository('App\Models\Integrations\ShoppingCartIntegration');
        $this->integrationValidation    = new IntegrationValidation();
    }

    public function getShippingApis (Request $request)
    {
        $getShippingApiIntegrations     = new GetShippingApiIntegrations($request->input());
        $getShippingApiIntegrations->validate();
        $getShippingApiIntegrations->clean();

        $query                          = $getShippingApiIntegrations->jsonSerialize();
        $results                        = $this->shippingApiIntegrationRepo->where($query);
        return response($results);
    }

    public function getShoppingCarts (Request $request)
    {
        $getShoppingCartIntegrations    = new GetShoppingCartIntegrations($request->input());
        $getShoppingCartIntegrations->validate();
        $getShoppingCartIntegrations->clean();

        $query                          = $getShoppingCartIntegrations->jsonSerialize();
        $results                        = $this->shoppingCartIntegrationRepo->where($query);
        return response($results);
    }

    public function show (Request $request)
    {
        $showIntegration                = new ShowIntegration();
        $showIntegration->setId($request->route('id'));
        $showIntegration->validate();
        $showIntegration->clean();

        $integration                    = $this->integrationValidation->idExists($showIntegration->getId());

        return response ($integration);
    }


    public function showIntegrationCredentials (Request $request)
    {
        $showIntegrationCredentials     = new ShowIntegrationCredentials();
        $showIntegrationCredentials->setId($request->route('id'));
        $showIntegrationCredentials->validate();
        $showIntegrationCredentials->clean();

        $integration                    = $this->integrationValidation->idExists($showIntegrationCredentials->getId());

        return response($integration->getIntegrationCredentials());
    }

    /**
     * @param   Request $request
     * @return  IntegrationWebHook[]
     */
    public function getWebHooks (Request $request)
    {
        $getIntegrationWebHooks         = new GetIntegrationWebHooks();
        $getIntegrationWebHooks->setId($request->route('id'));
        $getIntegrationWebHooks->validate();
        $getIntegrationWebHooks->clean();

        $integration                    = $this->integrationValidation->idExists($getIntegrationWebHooks->getId());

        $integrationWebHooks            = [];
        foreach ($integration->getIntegrationWebHooks() AS $integrationWebHook)
        {
            if ($integrationWebHook->isActive())
                $integrationWebHooks[]      = $integrationWebHook;
        }

        return response($integrationWebHooks);
    }


    public function getShippingApiCarriers (Request $request)
    {
        $showIntegration                = new ShowIntegration();
        $showIntegration->setId($request->route('id'));
        $showIntegration->validate();
        $showIntegration->clean();

        $shippingApiValidation          = new ShippingApiIntegrationValidation();
        $shippingApiIntegration         = $shippingApiValidation->idExists($showIntegration->getId());

        return response($shippingApiIntegration->getShippingApiCarriers());
    }

    public function getShippingApiServices (Request $request)
    {
        $showIntegration                = new ShowIntegration();
        $showIntegration->setId($request->route('id'));
        $showIntegration->validate();
        $showIntegration->clean();

        $shippingApiValidation          = new ShippingApiIntegrationValidation();
        $shippingApiIntegration         = $shippingApiValidation->idExists($showIntegration->getId());

        return response($shippingApiIntegration->getShippingApiServices());
    }
}