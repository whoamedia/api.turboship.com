<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\CreateWebHook;
use App\Http\Requests\Integrations\DeleteClientIntegrationWebHook;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($clientIntegration->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('ClientIntegration not found');

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

        if ($clientIntegration->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('ClientIntegration not found');

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

        if ($clientIntegration->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('ClientIntegration not found');

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
            $this->clientIntegrationRepo->saveAndCommit($clientIntegration);
        }

        return response($clientIntegration->getWebHooks(), 201);
    }

    /**
     * @param   Request $request
     * @return  string
     */
    public function deleteWebHook (Request $request)
    {
        $deleteClientIntegrationWebHook = new DeleteClientIntegrationWebHook();
        $deleteClientIntegrationWebHook->setId($request->route('id'));
        $deleteClientIntegrationWebHook->setClientWebHookId($request->route('clientWebHookId'));
        $deleteClientIntegrationWebHook->validate();
        $deleteClientIntegrationWebHook->clean();

        $clientIntegration              = $this->clientIntegrationValidation->idExists($deleteClientIntegrationWebHook->getId());

        if ($clientIntegration->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('ClientIntegration not found');

        $providedWebHook                = null;
        foreach ($clientIntegration->getWebHooks() AS $clientWebHook)
        {
            if ($clientWebHook->getId() == $deleteClientIntegrationWebHook->getClientWebHookId())
                $providedWebHook        = $clientWebHook;
        }

        if (is_null($providedWebHook))
            throw new BadRequestHttpException('clientIntegration does not have provided clientWebHookId');

        //  Delete the webHook in Shopify
        $shopifyWebHookRepository       = new ShopifyWebHookRepository($clientIntegration);
        $shopifyWebHookRepository->deleteWebHook($providedWebHook->getExternalId());

        $clientIntegration->removeWebHook($providedWebHook);
        $this->clientIntegrationRepo->saveAndCommit($clientIntegration);

        return response('', 204);
    }
}