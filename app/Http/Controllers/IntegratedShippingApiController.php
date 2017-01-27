<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShippingApis\CreateIntegratedShippingApi;
use App\Http\Requests\IntegratedShippingApis\GetIntegratedShippingApis;
use App\Http\Requests\Integrations\UpdateCredential;
use App\Http\Requests\Integrations\UpdateIntegration;
use App\Models\Integrations\Credential;
use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Integrations\Validation\ShippingApiIntegrationValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Services\CredentialService;
use EntityManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IntegratedShippingApiController extends BaseIntegratedServiceController
{

    /**
     * @var IntegratedShippingApiRepository
     */
    protected $integratedShippingApiRepo;


    public function __construct()
    {
        parent::__construct('IntegratedShippingApi');
        $this->integratedShippingApiRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedShippingApi');
    }


    public function index (Request $request)
    {
        $getIntegratedShoppingCarts         = new GetIntegratedShippingApis($request->input());
        $getIntegratedShoppingCarts->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getIntegratedShoppingCarts->validate();
        $getIntegratedShoppingCarts->clean();

        $query                              = $getIntegratedShoppingCarts->jsonSerialize();

        $results                            = $this->integratedShippingApiRepo->where($query, false);
        return response($results);
    }

    public function store (Request $request)
    {
        $createIntegratedShippingApi        = new CreateIntegratedShippingApi($request->input());
        $createIntegratedShippingApi->validate();
        $createIntegratedShippingApi->clean();

        $integratedShippingApi              = new IntegratedShippingApi();
        $integratedShippingApi->setName($createIntegratedShippingApi->getName());

        $shippingApiIntegrationValidation   = new ShippingApiIntegrationValidation();
        $shoppingCartIntegration            = $shippingApiIntegrationValidation->idExists($createIntegratedShippingApi->getIntegrationId());
        $integratedShippingApi->setIntegration($shoppingCartIntegration);

        $shipperValidation                  = new ShipperValidation();
        $shipper                            = $shipperValidation->idExists($createIntegratedShippingApi->getShipperId());
        $integratedShippingApi->setShipper($shipper);

        foreach ($createIntegratedShippingApi->getCredentials() AS $createCredential)
        {
            $credential                     = new Credential();
            $integrationCredential          = $shoppingCartIntegration->getIntegrationCredentialById($createCredential->getIntegrationCredentialId());
            if (is_null($integrationCredential))
                throw new BadRequestHttpException('integrationCredential not found');
            $credential->setIntegrationCredential($integrationCredential);
            $credential->setValue($createCredential->getValue());

            if ($integratedShippingApi->hasIntegrationCredential($integrationCredential))
                throw new BadRequestHttpException('duplicate integrationCredential');

            $integratedShippingApi->addCredential($credential);
        }

        foreach ($integratedShippingApi->getIntegration()->getIntegrationCredentials() AS $integrationCredential)
        {
            if ($integrationCredential->isRequired() && !$integratedShippingApi->hasIntegrationCredential($integrationCredential))
                throw new BadRequestHttpException($shoppingCartIntegration->getName() . ' ' . $integrationCredential->getName() . ' is required');
        }


        $credentialService                  = new CredentialService($integratedShippingApi);
        if (!$credentialService->validateCredentials())
            throw new BadRequestHttpException('The provided ' . $integratedShippingApi->getIntegration()->getName() . ' credentials are invalid');

        $this->integratedShippingApiRepo->saveAndCommit($integratedShippingApi);

        return response($integratedShippingApi);
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

        $this->integratedShippingApiRepo->saveAndCommit($integratedShoppingCart);
        return response($integratedShoppingCart);
    }

}