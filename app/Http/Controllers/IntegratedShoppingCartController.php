<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\CreateIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\DeleteIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\GetIntegratedShoppingCarts;
use App\Models\Integrations\IntegratedWebHook;
use App\Models\Integrations\Validation\IntegratedShoppingCartValidation;
use App\Models\Integrations\Validation\IntegratedWebHookValidation;
use App\Models\Integrations\Validation\IntegrationWebHookValidation;
use App\Repositories\Shopify\ShopifyWebHookRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IntegratedShoppingCartController extends BaseIntegratedServiceController
{

    /**
     * @var IntegratedShoppingCartValidation
     */
    private $integratedShoppingCartValidation;


    /**
     * IntegratedShoppingCartController constructor.
     */
    public function __construct ()
    {
        parent::__construct();
        $this->integratedShoppingCartValidation = new IntegratedShoppingCartValidation();
    }


    public function index (Request $request)
    {
        $getIntegratedShoppingCarts         = new GetIntegratedShoppingCarts($request->input());
        $getIntegratedShoppingCarts->validate();
        $getIntegratedShoppingCarts->clean();

        $query                              = $getIntegratedShoppingCarts->jsonSerialize();

        $results                            = $this->integratedShoppingCartRepo->where($query);
        return response($results);
    }


    public function show (Request $request)
    {
        $integratedShoppingCart         = $this->getIntegratedShoppingCart($request->route('id'));
        return response($integratedShoppingCart);
    }


    public function getWebHooks (Request $request)
    {
        $integratedShoppingCart         = $this->getIntegratedShoppingCart($request->route('id'));
        return response($integratedShoppingCart->getIntegratedWebHooks());
    }


    public function getAvailableWebHooks (Request $request)
    {
        $integratedShoppingCart         = $this->getIntegratedShoppingCart($request->route('id'));
        return response($integratedShoppingCart->getAvailableIntegratedWebHooks());
    }


    public function createWebHook (Request $request)
    {
        $createIntegratedWebHook            = new CreateIntegratedWebHook($request->input());
        $createIntegratedWebHook->setId($request->route('id'));
        $createIntegratedWebHook->validate();
        $createIntegratedWebHook->clean();

        $integratedShoppingCart             = $this->integratedShoppingCartValidation->idExists($createIntegratedWebHook->getId());
        $integrationWebHookValidation       = new IntegrationWebHookValidation();
        $integrationWebHookIds              = explode(',', $createIntegratedWebHook->getIntegrationWebHookIds());

        $shopifyWebHookRepository           = new ShopifyWebHookRepository($integratedShoppingCart);

        foreach ($integrationWebHookIds AS $integrationWebHookId)
        {
            $integrationWebHook             = $integrationWebHookValidation->idExists($integrationWebHookId);

            if (!$integratedShoppingCart->getIntegration()->hasIntegrationWebHook($integrationWebHook))
                throw new BadRequestHttpException('integrationWebHook does not belong to integration');

            if (!$integrationWebHook->isActive())
                throw new BadRequestHttpException('integrationWebHook is not active');

            if ($integratedShoppingCart->hasIntegrationWebHook($integrationWebHook))
                throw new BadRequestHttpException('integratedService is already registered for the integrationWebHook');

            $integratedWebHook              = new IntegratedWebHook();
            $integratedWebHook->setIntegratedService($integratedShoppingCart);
            $integratedWebHook->setIntegrationWebHook($integrationWebHook);
            $shopifyWebHookRepository->createWebHook($integratedWebHook);
            $integratedShoppingCart->addIntegratedWebHook($integratedWebHook);
            $this->integratedShoppingCartRepo->saveAndCommit($integratedShoppingCart);
        }

        return response($integratedShoppingCart->getIntegratedWebHooks(), 201);
    }


    public function deleteIntegratedWebHook (Request $request)
    {
        $deleteIntegratedWebHook            = new DeleteIntegratedWebHook();
        $deleteIntegratedWebHook->setId($request->route('id'));
        $deleteIntegratedWebHook->setIntegratedWebHookId($request->route('integratedWebHookId'));
        $deleteIntegratedWebHook->validate();
        $deleteIntegratedWebHook->clean();

        $integratedShoppingCart             = $this->integratedShoppingCartValidation->idExists($deleteIntegratedWebHook->getId());
        $integratedWebHookValidation        = new IntegratedWebHookValidation();

        $integratedWebHook                  = $integratedWebHookValidation->idExists($deleteIntegratedWebHook->getIntegratedWebHookId());
        if (!$integratedShoppingCart->hasIntegratedWebHook($integratedWebHook))
            throw new BadRequestHttpException('integratedShoppingCart does not have provided integratedWebHookId');

        $shopifyWebHookRepository       = new ShopifyWebHookRepository($integratedShoppingCart);
        $shopifyWebHookRepository->deleteWebHook($integratedWebHook->getExternalId());

        $integratedShoppingCart->removeIntegratedWebHook($integratedWebHook);
        $this->integratedShoppingCartRepo->saveAndCommit($integratedShoppingCart);

        return response ('', 204);
    }
}