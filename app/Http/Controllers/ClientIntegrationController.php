<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\CreateWebHook;
use App\Http\Requests\Integrations\GetClientIntegrationWebHooks;
use App\Models\Integrations\ClientWebHook;
use App\Models\Integrations\IntegrationWebHook;
use App\Models\Integrations\Validation\ClientIntegrationValidation;
use App\Models\Integrations\Validation\IntegrationWebHookValidation;
use App\Repositories\Doctrine\Integrations\ClientIntegrationRepository;
use App\Repositories\Shopify\ShopifyWebHookRepository;
use App\Utilities\IntegrationUtility;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ClientIntegrationController extends Controller
{

    /**
     * @var ClientIntegrationRepository
     */
    private $clientIntegrationRepo;

    /**
     * @var ClientIntegrationValidation
     */
    private $clientIntegrationValidation;


    public function __construct()
    {
        $this->clientIntegrationRepo    = EntityManager::getRepository('App\Models\Integrations\ClientIntegration');
        $this->clientIntegrationValidation = new ClientIntegrationValidation();
    }

    /**
     * @param   Request $request
     * @return  ClientWebHook[]
     */
    public function getWebHooks (Request $request)
    {
        $getClientIntegrationWebHooks   = new GetClientIntegrationWebHooks();
        $getClientIntegrationWebHooks->setId($request->route('id'));
        $getClientIntegrationWebHooks->validate();
        $getClientIntegrationWebHooks->clean();

        $clientIntegration              = $this->clientIntegrationValidation->idExists($getClientIntegrationWebHooks->getId());
        return response ($clientIntegration->getWebHooks());
    }

    /**
     * See which integration webHooks thee clientIntegration is not using
     * @param   Request $request
     * @return  IntegrationWebHook[]
     */
    public function getAvailableWebHooks (Request $request)
    {
        $getClientIntegrationWebHooks   = new GetClientIntegrationWebHooks();
        $getClientIntegrationWebHooks->setId($request->route('id'));
        $getClientIntegrationWebHooks->validate();
        $getClientIntegrationWebHooks->clean();

        $clientIntegration              = $this->clientIntegrationValidation->idExists($getClientIntegrationWebHooks->getId());

        $integrationWebHooks            = $clientIntegration->getIntegration()->getWebHooks();

        $response                       = [];
        foreach ($integrationWebHooks AS $integrationWebHook)
        {
            if (!$clientIntegration->hasIntegrationWebHook($integrationWebHook) && $integrationWebHook->isActive())
                $response[]             = $integrationWebHook;
        }

        return response($response);
    }

    /**
     * @param   Request $request
     * @return  ClientWebHook[]
     */
    public function createWebHook (Request $request)
    {
        $createWebHook                  = new CreateWebHook($request->input());
        $createWebHook->setId($request->route('id'));
        $createWebHook->validate();
        $createWebHook->clean();

        $clientIntegration              = $this->clientIntegrationValidation->idExists($createWebHook->getId());

        if ($clientIntegration->getIntegration()->getId() != IntegrationUtility::SHOPIFY_ID)
            throw new BadRequestHttpException('WebHooks are currently only enabled for Shopify');

        $integrationWebHookValidation   = new IntegrationWebHookValidation();
        $integrationWebHookIds          = explode(',', $createWebHook->getIntegrationWebHookIds());

        $shopifyWebHookRepository       = new ShopifyWebHookRepository($clientIntegration);

        foreach ($integrationWebHookIds AS $integrationWebHookId)
        {
            $integrationWebHook         = $integrationWebHookValidation->idExists($integrationWebHookId);

            if (!$clientIntegration->getIntegration()->hasWebHook($integrationWebHook))
                throw new BadRequestHttpException('integrationWebHook does not belong to integration');

            if (!$integrationWebHook->isActive())
                throw new BadRequestHttpException('integrationWebHook is not active');

            if ($clientIntegration->hasIntegrationWebHook($integrationWebHook))
                throw new BadRequestHttpException('clientIntegration is already registered for the webHook');

            $clientWebHook              = new ClientWebHook();
            $clientWebHook->setClientIntegration($clientIntegration);
            $clientWebHook->setIntegrationWebHook($integrationWebHook);
            $shopifyWebHookRepository->createWebHook($clientWebHook);
            $clientIntegration->addWebHook($clientWebHook);
        }

        $this->clientIntegrationRepo->saveAndCommit($clientIntegration);

        return response($clientIntegration->getWebHooks(), 201);
    }
}