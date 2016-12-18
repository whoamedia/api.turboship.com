<?php

namespace App\Repositories\Shopify;


use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Integrations\Shopify\ShopifyConfiguration;
use App\Integrations\Shopify\ShopifyIntegration;
use App\Utilities\CredentialUtility;
use EntityManager;

class BaseShopifyRepository
{

    /**
     * @var ShopifyIntegration
     */
    protected $shopifyIntegration;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientRepository
     */
    protected $clientRepo;


    /**
     * BaseShopifyRepository constructor.
     * @param Client                    $client
     * @param ShopifyIntegration|null       $shopifyIntegration
     */
    public function __construct(Client $client, $shopifyIntegration = null)
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->clientValidation         = new ClientValidation($this->clientRepo);
        $this->client                   = $client;

        if (!is_null($shopifyIntegration))
            $this->shopifyIntegration   = $shopifyIntegration;
        else
        {
            $credentialUtility          = new CredentialUtility($this->client);
            $apiKey                     = $credentialUtility->getShopifyApiKey()->getValue();
            $password                   = $credentialUtility->getShopifyPassword()->getValue();
            $hostName                   = $credentialUtility->getShopifyHostName()->getValue();

            $shopifyConfiguration       = new ShopifyConfiguration();
            $shopifyConfiguration->setApiKey($apiKey);
            $shopifyConfiguration->setPassword($password);
            $shopifyConfiguration->setHostName($hostName);

            $this->shopifyIntegration       = new ShopifyIntegration($shopifyConfiguration);
        }
    }

    /**
     * @return ShopifyIntegration
     */
    public function getShopifyIntegration()
    {
        return $this->shopifyIntegration;
    }

    /**
     * @param ShopifyIntegration $shopifyIntegration
     */
    public function setShopifyIntegration($shopifyIntegration)
    {
        $this->shopifyIntegration = $shopifyIntegration;
    }

}