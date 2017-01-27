<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\CreateIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\DeleteIntegratedWebHook;
use App\Http\Requests\IntegratedShoppingCarts\GetIntegratedShoppingCarts;
use App\Http\Requests\Integrations\CreateIntegratedShoppingCart;
use App\Http\Requests\Integrations\UpdateCredential;
use App\Http\Requests\Integrations\UpdateIntegration;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Integrations\Credential;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Integrations\IntegratedWebHook;
use App\Models\Integrations\Validation\IntegratedWebHookValidation;
use App\Models\Integrations\Validation\IntegrationWebHookValidation;
use App\Models\Integrations\Validation\ShoppingCartApiValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use App\Services\CredentialService;
use App\Services\Shopify\ShopifyService;
use Illuminate\Http\Request;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegratedShoppingCartController extends BaseIntegratedServiceController
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
        parent::__construct('IntegratedShoppingCart');
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

    public function update (Request $request)
    {
        $updateIntegration                  = new UpdateIntegration($request->input());
        $updateIntegration->setId($request->route('id'));
        $updateIntegration->validate();
        $updateIntegration->clean();

        $integratedShoppingCart             = parent::getIntegratedServiceFromRoute($updateIntegration->getId());
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

        return response($integratedShoppingCart);
    }


    public function createWebHook (Request $request)
    {
        $createIntegratedWebHook            = new CreateIntegratedWebHook($request->input());
        $createIntegratedWebHook->setId($request->route('id'));
        $createIntegratedWebHook->validate();
        $createIntegratedWebHook->clean();

        $integratedShoppingCart             = parent::getIntegratedServiceFromRoute($request->route('id'));
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

        $integratedShoppingCart             = parent::getIntegratedServiceFromRoute($request->route('id'));
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

}