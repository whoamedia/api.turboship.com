<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\GetShippingApiIntegrations;
use App\Http\Requests\Integrations\GetShoppingCartIntegrations;
use App\Http\Requests\Integrations\ShowIntegration;
use App\Models\Integrations\Integration;
use App\Models\Integrations\IntegrationWebHook;
use App\Models\Integrations\ShippingApiIntegration;
use App\Models\Integrations\Validation\IntegrationValidation;
use App\Models\Integrations\Validation\ShippingApiIntegrationValidation;
use App\Repositories\Doctrine\Integrations\IntegrationRepository;
use App\Repositories\Doctrine\Integrations\ShippingApiIntegrationRepository;
use App\Repositories\Doctrine\Integrations\ShoppingCartIntegrationRepository;
use Illuminate\Http\Request;
use EntityManager;

class IntegrationController extends BaseAuthController
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
        $results                        = $this->shippingApiIntegrationRepo->where($query, false);
        return response($results);
    }

    public function getShoppingCarts (Request $request)
    {
        $getShoppingCartIntegrations    = new GetShoppingCartIntegrations($request->input());
        $getShoppingCartIntegrations->validate();
        $getShoppingCartIntegrations->clean();

        $query                          = $getShoppingCartIntegrations->jsonSerialize();
        $results                        = $this->shoppingCartIntegrationRepo->where($query, false);
        return response($results);
    }

    public function show (Request $request)
    {
        $integration                    = $this->getIntegrationFromRoute($request->route('id'));
        return response ($integration);
    }


    public function showIntegrationCredentials (Request $request)
    {
        $integration                    = $this->getIntegrationFromRoute($request->route('id'));
        return response($integration->getIntegrationCredentials());
    }

    /**
     * @param   Request $request
     * @return  IntegrationWebHook[]
     */
    public function getWebHooks (Request $request)
    {
        $integration                    = $this->getIntegrationFromRoute($request->route('id'));

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
        $shippingApiIntegration         = $this->getShippingApiIntegrationFromRoute($request->route('id'));
        return response($shippingApiIntegration->getShippingApiCarriers());
    }

    public function getShippingApiServices (Request $request)
    {
        $shippingApiIntegration         = $this->getShippingApiIntegrationFromRoute($request->route('id'));
        return response($shippingApiIntegration->getShippingApiServices());
    }


    /**
     * @param   int     $id
     * @return  Integration
     */
    private function getIntegrationFromRoute ($id)
    {
        $showIntegration                = new ShowIntegration();
        $showIntegration->setId($id);
        $showIntegration->validate();
        $showIntegration->clean();

        $integrationValidation          = new IntegrationValidation();
        return $integrationValidation->idExists($showIntegration->getId());
    }

    /**
     * @param   int     $id
     * @return  ShippingApiIntegration
     */
    private function getShippingApiIntegrationFromRoute ($id)
    {
        $showIntegration                = new ShowIntegration();
        $showIntegration->setId($id);
        $showIntegration->validate();
        $showIntegration->clean();

        $shippingApiValidation          = new ShippingApiIntegrationValidation();
        return $shippingApiValidation->idExists($showIntegration->getId());
    }
}