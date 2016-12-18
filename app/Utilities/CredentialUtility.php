<?php

namespace App\Utilities;


use App\Models\CMS\Client;
use App\Models\Integrations\ClientCredential;
use App\Repositories\Doctrine\Integrations\ClientCredentialRepository;
use EntityManager;

class CredentialUtility
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ClientCredentialRepository
     */
    private $clientCredentialRepo;

    public function __construct(Client $client)
    {
        $this->client                   = $client;
        $this->clientCredentialRepo     = EntityManager::getRepository('App\Models\Integrations\ClientCredential');
    }

    /**
     * @return  ClientCredential|null
     */
    public function getShopifyApiKey ()
    {
        $query      = [
            'clientIds'             => $this->client->getId(),
            'integrationCredentialIds' => IntegrationCredentialUtility::SHOPIFY_API_KEY_ID
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
            'clientIds'                 => $this->client->getId(),
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
            'clientIds'                 => $this->client->getId(),
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