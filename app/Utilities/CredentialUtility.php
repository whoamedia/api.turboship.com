<?php

namespace App\Utilities;


use App\Models\CMS\Client;
use App\Models\Integrations\ClientCredential;
use App\Models\Integrations\ClientIntegration;
use App\Repositories\Doctrine\Integrations\ClientCredentialRepository;
use EntityManager;

class CredentialUtility
{

    /**
     * @var ClientIntegration
     */
    private $clientIntegration;

    /**
     * @var ClientCredentialRepository
     */
    private $clientCredentialRepo;

    public function __construct(ClientIntegration $clientIntegration)
    {
        $this->clientIntegration        = $clientIntegration;
        $this->clientCredentialRepo     = EntityManager::getRepository('App\Models\Integrations\ClientCredential');
    }

    /**
     * @return  ClientCredential
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
     * @return  ClientCredential
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
     * @return  ClientCredential
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
     * @return  ClientCredential
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
     * @return  ClientCredential
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