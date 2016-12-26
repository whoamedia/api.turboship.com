<?php

namespace App\Services;


use App\Models\Integrations\Credential;
use App\Models\Integrations\IntegratedService;
use App\Repositories\Doctrine\Integrations\CredentialRepository;
use App\Utilities\IntegrationCredentialUtility;
use EntityManager;

class CredentialService
{

    /**
     * @var IntegratedService
     */
    private $integratedService;

    /**
     * @var CredentialRepository
     */
    private $clientCredentialRepo;

    public function __construct(IntegratedService $integratedService)
    {
        $this->integratedService        = $integratedService;
        $this->clientCredentialRepo     = EntityManager::getRepository('App\Models\Integrations\Credential');
    }

    /**
     * @return  Credential
     */
    public function getShopifyApiKey ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_API_KEY_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

    /**
     * @return  Credential
     */
    public function getShopifyPassword ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_PASSWORD_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

    /**
     * @return  Credential
     */
    public function getShopifyHostName ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_HOSTNAME_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

    /**
     * @return  Credential
     */
    public function getShopifySharedSecret ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_SHARED_SECRET_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }


    /**
     * @return  Credential
     */
    public function getEasyPostApiKey ()
    {
        $query      = [
            'integratedServiceIds'      => $this->integratedService->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::EASYPOST_API_KEY_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

}