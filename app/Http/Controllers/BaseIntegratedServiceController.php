<?php

namespace App\Http\Controllers;


use App\Http\Requests\Integrations\UpdateIntegrationCredentials;
use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Doctrine\Integrations\IntegratedServiceRepository;
use App\Services\CredentialService;
use EntityManager;
use Illuminate\Http\Request;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseIntegratedServiceController extends BaseAuthController
{

    /**
     * @var IntegratedServiceRepository
     */
    protected $integratedServiceRepo;

    /**
     * @var ClientRepository
     */
    protected $clientRepo;

    /**
     * @var string
     */
    private $object;

    /**
     * BaseIntegratedServiceController constructor.
     * @param   string      $object
     */
    public function __construct ($object)
    {
        $this->object                       = $object;
        $this->integratedServiceRepo        = EntityManager::getRepository('App\Models\Integrations\IntegratedService');
        $this->clientRepo                   = EntityManager::getRepository('App\Models\CMS\Client');
    }

    public function show (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedServiceFromRoute($request->route('id'));
        return response($integratedShoppingCart);
    }

    public function getCredentials (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedServiceFromRoute($request->route('id'));
        return response($integratedShoppingCart->getCredentials());
    }

    public function updateCredentials (Request $request)
    {
        $updateIntegrationCredentials       = new UpdateIntegrationCredentials($request->input());
        $updateIntegrationCredentials->setId($request->route('id'));
        $updateIntegrationCredentials->validate();
        $updateIntegrationCredentials->clean();

        $integratedShoppingCart             = $this->getIntegratedServiceFromRoute($updateIntegrationCredentials->getId());
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

        $this->integratedServiceRepo->saveAndCommit($integratedShoppingCart);
        return response($integratedShoppingCart->getCredentials());
    }

    public function getWebHooks (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedServiceFromRoute($request->route('id'));
        return response($integratedShoppingCart->getIntegratedWebHooks());
    }

    public function getAvailableWebHooks (Request $request)
    {
        $integratedShoppingCart             = $this->getIntegratedServiceFromRoute($request->route('id'));
        return response($integratedShoppingCart->getAvailableIntegratedWebHooks());
    }

    /**
     * @param   int     $id
     * @return  IntegratedShoppingCart|IntegratedShippingApi
     */
    protected function getIntegratedServiceFromRoute ($id)
    {
        if (is_null($id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($id)))
            throw new BadRequestHttpException('id must be integer');

        $integratedService                  = $this->integratedServiceRepo->getOneById($id);
        if (is_null($integratedService))
            throw new NotFoundHttpException($this->object . ' not found');

        if (get_class($integratedService) != 'App\Models\Integrations\\' . $this->object)
            throw new NotFoundHttpException($this->object . ' not found');

        return $integratedService;
    }

}