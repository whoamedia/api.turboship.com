<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShippingApis\CreateIntegratedShippingApi;
use App\Http\Requests\IntegratedShippingApis\GetIntegratedShippingApis;
use App\Models\Integrations\Credential;
use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Integrations\Validation\ShippingApiIntegrationValidation;
use App\Models\Shipments\Validation\ShipperValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Services\CredentialService;
use EntityManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

}