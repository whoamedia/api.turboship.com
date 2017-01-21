<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\CreateIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\DeleteIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\GetIntegratedShoppingCarts;
use App\Http\Requests\IntegratedShoppingCarts\ShowIntegratedShoppingCart;
use App\Http\Requests\Integrations\UpdateIntegrationCredentials;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Integrations\IntegratedWebHook;
use App\Models\Integrations\Validation\IntegratedShoppingCartValidation;
use App\Models\Integrations\Validation\IntegratedWebHookValidation;
use App\Models\Integrations\Validation\IntegrationWebHookValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use App\Repositories\Shopify\BaseShopifyRepository;
use App\Repositories\Shopify\ShopifyWebHookRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegratedShoppingCartController extends BaseAuthController
{

    /**
     * @var IntegratedShoppingCartRepository
     */
    protected $integratedShoppingCartRepo;

    /**
     * IntegratedShoppingCartController constructor.
     */
    public function __construct ()
    {
        $this->integratedShoppingCartRepo   = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
    }


    public function index (Request $request)
    {
        $getIntegratedShoppingCarts         = new GetIntegratedShoppingCarts($request->input());
        $getIntegratedShoppingCarts->validate();
        $getIntegratedShoppingCarts->clean();

        $query                              = $getIntegratedShoppingCarts->jsonSerialize();

        $results                            = $this->integratedShoppingCartRepo->where($query, false);
        return response($results);
    }


    public function show (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        return response($integratedShoppingCart);
    }

    public function getCredentials (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        return response($integratedShoppingCart->getCredentials());
    }

    public function updateCredentials (Request $request)
    {
        $updateIntegrationCredentials       = new UpdateIntegrationCredentials($request->input());
        $updateIntegrationCredentials->setId($request->route('id'));
        $updateIntegrationCredentials->validate();
        $updateIntegrationCredentials->clean();

        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($updateIntegrationCredentials->getId());
        foreach ($updateIntegrationCredentials->getCredentials() AS $updateCredential)
        {
            $integrationCredential          = $integratedShoppingCart->getCredentialById($updateCredential->getIntegrationCredentialId());
            if (is_null($integrationCredential))
                throw new NotFoundHttpException('integrationCredentialId not found');

            $integrationCredential->setValue($updateCredential->getValue());
        }

        $baseShopifyRepository              = new BaseShopifyRepository($integratedShoppingCart);
        if (!$baseShopifyRepository->validateCredentials())
            throw new BadRequestHttpException('The provided credentials are invalid');

        $this->integratedShoppingCartRepo->saveAndCommit($integratedShoppingCart);
        return response($integratedShoppingCart->getCredentials());
    }

    public function getWebHooks (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        return response($integratedShoppingCart->getIntegratedWebHooks());
    }


    public function getAvailableWebHooks (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        return response($integratedShoppingCart->getAvailableIntegratedWebHooks());
    }


    public function createWebHook (Request $request)
    {
        $createIntegratedWebHook            = new CreateIntegratedWebHook($request->input());
        $createIntegratedWebHook->setId($request->route('id'));
        $createIntegratedWebHook->validate();
        $createIntegratedWebHook->clean();

        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
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

        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
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

    /**
     * @param   int     $id
     * @return  IntegratedShoppingCart
     */
    private function getIntegratedShoppingCartFromRoute ($id)
    {
        $showIntegratedShoppingCart         = new ShowIntegratedShoppingCart();
        $showIntegratedShoppingCart->setId($id);
        $showIntegratedShoppingCart->validate();
        $showIntegratedShoppingCart->clean();

        $integratedShoppingCartValidation   = new IntegratedShoppingCartValidation();
        return $integratedShoppingCartValidation->idExists($showIntegratedShoppingCart->getId());
    }
}