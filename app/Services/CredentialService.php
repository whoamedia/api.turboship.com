<?php

namespace App\Services;


use App\Models\Integrations\Credential;
use App\Models\Integrations\ClientIntegration;
use App\Repositories\Doctrine\Integrations\CredentialRepository;
use App\Utilities\IntegrationCredentialUtility;
use EntityManager;

class CredentialService
{

    /**
     * @var ClientIntegration
     */
    private $clientIntegration;

    /**
     * @var CredentialRepository
     */
    private $clientCredentialRepo;

    public function __construct(ClientIntegration $clientIntegration)
    {
        $this->clientIntegration        = $clientIntegration;
        $this->clientCredentialRepo     = EntityManager::getRepository('App\Models\Integrations\Credential');
    }

    /**
     * @return  Credential
     */
    public function getShopifyApiKey ()
    {
        $query      = [
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
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
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
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
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
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
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
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
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::EASYPOST_API_KEY_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return $result[0];
    }

}