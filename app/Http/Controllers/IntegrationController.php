<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\GetIntegrationWebHooks;
use App\Models\Integrations\IntegrationWebHook;
use App\Models\Integrations\Validation\IntegrationValidation;
use App\Repositories\Doctrine\Integrations\IntegrationWebHookRepository;
use Illuminate\Http\Request;
use EntityManager;

class IntegrationController
{

    /**
     * @var IntegrationValidation
     */
    private $integrationValidation;

    /**
     * @var IntegrationWebHookRepository
     */
    private $integrationWebHookRepo;


    public function __construct()
    {
        $this->integrationValidation    = new IntegrationValidation();
        $this->integrationWebHookRepo   = EntityManager::getRepository('App\Models\Integrations\IntegrationWebHook');
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

        $query  = [
            'integrationIds'    => $integration->getId(),
            'isActive'          => true,
        ];

        $results                        = $this->integrationWebHookRepo->where($query);

        return response($results);
    }
}