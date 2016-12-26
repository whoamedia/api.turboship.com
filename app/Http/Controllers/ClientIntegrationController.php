<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\CreateIntegratedWebHook;
use App\Http\Requests\Integrations\DeleteIntegratedWebHook;
use App\Http\Requests\Integrations\GetIntegratedWebHooks;
use App\Models\Integrations\IntegratedWebHook;
use App\Models\Integrations\IntegrationWebHook;
use App\Models\Integrations\Validation\IntegratedServiceValidation;
use App\Models\Integrations\Validation\IntegrationWebHookValidation;
use App\Repositories\Doctrine\Integrations\IntegratedServiceRepository;
use App\Repositories\Shopify\ShopifyWebHookRepository;
use App\Utilities\IntegrationUtility;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientIntegrationController extends Controller
{

    /**
     * @var IntegratedServiceRepository
     */
    private $integratedServiceRepo;

    /**
     * @var IntegratedServiceValidation
     */
    private $integratedServiceValidation;


    public function __construct()
    {
        $this->integratedServiceRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedService');
        $this->integratedServiceValidation = new IntegratedServiceValidation();
    }

    /**
     * @param   Request $request
     * @return  IntegratedWebHook[]
     */
    public function getWebHooks (Request $request)
    {
        $getIntegratedWebHooks   = new GetIntegratedWebHooks();
        $getIntegratedWebHooks->setId($request->route('id'));
        $getIntegratedWebHooks->validate();
        $getIntegratedWebHooks->clean();

        $integratedService              = $this->integratedServiceValidation->idExists($getIntegratedWebHooks->getId());

        if ($integratedService->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('IntegratedService not found');

        return response ($integratedService->getWebHooks());
    }

    /**
     * See which integration webHooks thee integratedService is not using
     * @param   Request $request
     * @return  IntegrationWebHook[]
     */
    public function getAvailableWebHooks (Request $request)
    {
        $getIntegratedWebHooks   = new GetIntegratedWebHooks();
        $getIntegratedWebHooks->setId($request->route('id'));
        $getIntegratedWebHooks->validate();
        $getIntegratedWebHooks->clean();

        $integratedService              = $this->integratedServiceValidation->idExists($getIntegratedWebHooks->getId());

        if ($integratedService->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('IntegratedService not found');

        $integrationWebHooks            = $integratedService->getIntegration()->getWebHooks();

        $response                       = [];
        foreach ($integrationWebHooks AS $integrationWebHook)
        {
            if (!$integratedService->hasIntegrationWebHook($integrationWebHook) && $integrationWebHook->isActive())
                $response[]             = $integrationWebHook;
        }

        return response($response);
    }

    /**
     * @param   Request $request
     * @return  IntegratedWebHook[]
     */
    public function createWebHook (Request $request)
    {
        $createWebHook                  = new CreateIntegratedWebHook($request->input());
        $createWebHook->setId($request->route('id'));
        $createWebHook->validate();
        $createWebHook->clean();

        $integratedService              = $this->integratedServiceValidation->idExists($createWebHook->getId());

        if ($integratedService->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('IntegratedService not found');

        if ($integratedService->getIntegration()->getId() != IntegrationUtility::SHOPIFY_ID)
            throw new BadRequestHttpException('WebHooks are currently only enabled for Shopify');

        $integrationWebHookValidation   = new IntegrationWebHookValidation();
        $integrationWebHookIds          = explode(',', $createWebHook->getIntegrationWebHookIds());

        $shopifyWebHookRepository       = new ShopifyWebHookRepository($integratedService);

        foreach ($integrationWebHookIds AS $integrationWebHookId)
        {
            $integrationWebHook         = $integrationWebHookValidation->idExists($integrationWebHookId);

            if (!$integratedService->getIntegration()->hasWebHook($integrationWebHook))
                throw new BadRequestHttpException('integrationWebHook does not belong to integration');

            if (!$integrationWebHook->isActive())
                throw new BadRequestHttpException('integrationWebHook is not active');

            if ($integratedService->hasIntegrationWebHook($integrationWebHook))
                throw new BadRequestHttpException('integratedService is already registered for the webHook');

            $integratedWebHook              = new IntegratedWebHook();
            $integratedWebHook->setIntegratedService($integratedService);
            $integratedWebHook->setIntegrationWebHook($integrationWebHook);
            $shopifyWebHookRepository->createWebHook($integratedWebHook);
            $integratedService->addIntegratedWebHook($integratedWebHook);
            $this->integratedServiceRepo->saveAndCommit($integratedService);
        }

        return response($integratedService->getWebHooks(), 201);
    }

    /**
     * @param   Request $request
     * @return  string
     */
    public function deleteWebHook (Request $request)
    {
        $deleteIntegratedServiceWebHook = new DeleteIntegratedWebHook();
        $deleteIntegratedServiceWebHook->setId($request->route('id'));
        $deleteIntegratedServiceWebHook->setIntegratedWebHookId($request->route('integratedWebHookId'));
        $deleteIntegratedServiceWebHook->validate();
        $deleteIntegratedServiceWebHook->clean();

        $integratedService              = $this->integratedServiceValidation->idExists($deleteIntegratedServiceWebHook->getId());

        if ($integratedService->getClient()->getOrganization()->getId() != \Auth::getUser()->getOrganization()->getId())
            throw new NotFoundHttpException('IntegratedService not found');

        $providedWebHook                = null;
        foreach ($integratedService->getWebHooks() AS $integratedWebHook)
        {
            if ($integratedWebHook->getId() == $deleteIntegratedServiceWebHook->getIntegratedWebHookId())
                $providedWebHook        = $integratedWebHook;
        }

        if (is_null($providedWebHook))
            throw new BadRequestHttpException('integratedService does not have provided integratedWebHookId');

        //  Delete the webHook in Shopify
        $shopifyWebHookRepository       = new ShopifyWebHookRepository($integratedService);
        $shopifyWebHookRepository->deleteWebHook($providedWebHook->getExternalId());

        $integratedService->removeIntegratedWebHook($providedWebHook);
        $this->integratedServiceRepo->saveAndCommit($integratedService);

        return response('', 204);
    }
}