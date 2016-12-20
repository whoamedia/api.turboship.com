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
     * @return  ClientCredential|null
     */
    public function getShopifyApiKey ()
    {
        $query      = [
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_API_KEY_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return (sizeof($result) == 1) ? $result[0] : null;
    }

    /**
     * @return  ClientCredential|null
     */
    public function getShopifyPassword ()
    {
        $query      = [
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_PASSWORD_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return (sizeof($result) == 1) ? $result[0] : null;
    }

    /**
     * @return  ClientCredential|null
     */
    public function getShopifyHostName ()
    {
        $query      = [
            'clientIntegrationIds'      => $this->clientIntegration->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::SHOPIFY_HOSTNAME_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return (sizeof($result) == 1) ? $result[0] : null;
    }


    /**
     * @return  ClientCredential|null
     */
    public function getEasyPostApiKey ()
    {
        $query      = [
            'clientIds'                 => $this->client->getId(),
            'integrationCredentialIds'  => IntegrationCredentialUtility::EASYPOST_API_KEY_ID
        ];

        $result                         = $this->clientCredentialRepo->where($query);

        return (sizeof($result) == 1) ? $result[0] : null;
    }

}