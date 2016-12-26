<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\GetIntegrations;
use App\Http\Requests\Integrations\GetIntegrationWebHooks;
use App\Http\Requests\Integrations\ShowIntegration;
use App\Http\Requests\Integrations\ShowIntegrationCredentials;
use App\Models\Integrations\IntegrationWebHook;
use App\Models\Integrations\Validation\IntegrationValidation;
use App\Repositories\Doctrine\Integrations\IntegrationRepository;
use Illuminate\Http\Request;
use EntityManager;

class IntegrationController
{

    /**
     * @var IntegrationRepository
     */
    private $integrationRepo;

    /**
     * @var IntegrationValidation
     */
    private $integrationValidation;


    public function __construct()
    {
        $this->integrationRepo          = EntityManager::getRepository('App\Models\Integrations\Integration');
        $this->integrationValidation    = new IntegrationValidation();
    }

    public function index (Request $request)
    {
        $getIntegrations                = new GetIntegrations($request->input());
        $getIntegrations->validate();
        $getIntegrations->clean();

        $query                          = $getIntegrations->jsonSerialize();

        $results                        = $this->integrationRepo->where($query);
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
}