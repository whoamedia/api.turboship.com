<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\CreateIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\DeleteIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\GetIntegratedShoppingCarts;
use App\Http\Requests\IntegratedShoppingCarts\ShowIntegratedShoppingCart;
use App\Http\Requests\Integrations\CreateIntegratedShoppingCart;
use App\Http\Requests\Integrations\UpdateCredential;
use App\Http\Requests\Integrations\UpdateIntegration;
use App\Http\Requests\Integrations\UpdateIntegrationCredentials;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Integrations\Credential;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Integrations\IntegratedWebHook;
use App\Models\Integrations\Validation\IntegratedShoppingCartValidation;
use App\Models\Integrations\Validation\IntegratedWebHookValidation;
use App\Models\Integrations\Validation\IntegrationWebHookValidation;
use App\Models\Integrations\Validation\ShoppingCartApiValidation;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use App\Services\CredentialService;
use App\Services\Shopify\ShopifyService;
use Illuminate\Http\Request;
use jamesvweston\Utilities\ArrayUtil AS AU;
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
     * @var ClientRepository
     */
    protected $clientRepo;

    /**
     * IntegratedShoppingCartController constructor.
     */
    public function __construct ()
    {
        $this->integratedShoppingCartRepo   = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
        $this->clientRepo                   = EntityManager::getRepository('App\Models\CMS\Client');
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

    public function update (Request $request)
    {
        $updateIntegration                  = new UpdateIntegration($request->input());
        $updateIntegration->setId($request->route('id'));
        $updateIntegration->validate();
        $updateIntegration->clean();

        $integratedShoppingCart             = $this->getIntegratedShoppingCartFromRoute($updateIntegration->getId());
        if (!is_null($updateIntegration->getName()))
            $integratedShoppingCart->setName($updateIntegration->getName());

        if ($request->has('credentials'))
        {
            $providedCredentials            = $request->input('credentials');
            if (!AU::isArrays($providedCredentials) || empty($providedCredentials))
                throw new BadRequestHttpException('credentials should be array of arrays');
            foreach ($providedCredentials AS $item)
            {
                $updateCredential           = new UpdateCredential($item);
                $updateCredential->validate();
                $updateCredential->clean();

                $integrationCredential          = $integratedShoppingCart->getCredentialById($updateCredential->getIntegrationCredentialId());
                if (is_null($integrationCredential))
                    throw new NotFoundHttpException('integrationCredentialId not found');
                $integrationCredential->setValue($updateCredential->getValue());
            }
            $credentialService                  = new CredentialService($integratedShoppingCart);
            if (!$credentialService->validateCredentials())
                throw new BadRequestHttpException('The provided ' . $integratedShoppingCart->getIntegration()->getName() . ' credentials are invalid');
        }

        $this->integratedShoppingCartRepo->saveAndCommit($integratedShoppingCart);
        return response($integratedShoppingCart);
    }

    public function store (Request $request)
    {
        $createIntegratedShoppingCart       = new CreateIntegratedShoppingCart($request->input());
        $createIntegratedShoppingCart->validate();
        $createIntegratedShoppingCart->clean();

        $integratedShoppingCart             = new IntegratedShoppingCart();
        $integratedShoppingCart->setName($createIntegratedShoppingCart->getName());

        $shoppingCartApiValidation          = new ShoppingCartApiValidation();
        $shoppingCartIntegration            = $shoppingCartApiValidation->idExists($createIntegratedShoppingCart->getIntegrationId());
        $integratedShoppingCart->setIntegration($shoppingCartIntegration);

        $clientValidation                   = new ClientValidation($this->clientRepo);
        $client                             = $clientValidation->idExists($createIntegratedShoppingCart->getClientId());
        $integratedShoppingCart->setClient($client);

        foreach ($createIntegratedShoppingCart->getCredentials() AS $createCredential)
        {
            $credential                     = new Credential();
            $integrationCredential          = $shoppingCartIntegration->getIntegrationCredentialById($createCredential->getIntegrationCredentialId());
            if (is_null($integrationCredential))
                throw new BadRequestHttpException('integrationCredential not found');
            $credential->setIntegrationCredential($integrationCredential);
            $credential->setValue($createCredential->getValue());

            if ($integratedShoppingCart->hasIntegrationCredential($integrationCredential))
                throw new BadRequestHttpException('duplicate integrationCredential');

            $integratedShoppingCart->addCredential($credential);
        }

        foreach ($integratedShoppingCart->getIntegration()->getIntegrationCredentials() AS $integrationCredential)
        {
            if ($integrationCredential->isRequired() && !$integratedShoppingCart->hasIntegrationCredential($integrationCredential))
                throw new BadRequestHttpException($shoppingCartIntegration->getName() . ' ' . $integrationCredential->getName() . ' is required');
        }


        $credentialService                  = new CredentialService($integratedShoppingCart);
        if (!$credentialService->validateCredentials())
            throw new BadRequestHttpException('The provided ' . $integratedShoppingCart->getIntegration()->getName() . ' credentials are invalid');

        $this->integratedShoppingCartRepo->saveAndCommit($integratedShoppingCart);

        foreach ($createIntegratedShoppingCart->getWebHooks() AS $createWebHook)
        {

        }

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

        $credentialService                  = new CredentialService($integratedShoppingCart);
        if (!$credentialService->validateCredentials())
            throw new BadRequestHttpException('The provided ' . $integratedShoppingCart->getIntegration()->getName() . ' credentials are invalid');

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

        $shopifyService                     = new ShopifyService($integratedShoppingCart);

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
            $shopifyService->createWebHook($integratedWebHook);
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

        $shopifyService                     = new ShopifyService($integratedShoppingCart);
        $shopifyService->deleteWebHook($integratedWebHook->getExternalId());

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