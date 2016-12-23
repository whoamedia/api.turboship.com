<?php

namespace App\Repositories\Shopify;


use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Integrations\ClientIntegration;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Integrations\Shopify\ShopifyConfiguration;
use App\Integrations\Shopify\ShopifyIntegration;
use App\Services\CredentialService;
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
     * @var ClientIntegration
     */
    protected $clientIntegration;

    /**
     * @var ClientRepository
     */
    protected $clientRepo;


    /**
     * BaseShopifyRepository constructor.
     * @param ClientIntegration             $clientIntegration
     * @param ShopifyIntegration|null       $shopifyIntegration
     */
    public function __construct(ClientIntegration $clientIntegration, $shopifyIntegration = null)
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->clientValidation         = new ClientValidation($this->clientRepo);
        $this->clientIntegration        = $clientIntegration;
        $this->client                   = $this->clientIntegration->getClient();

        if (!is_null($shopifyIntegration))
            $this->shopifyIntegration   = $shopifyIntegration;
        else
        {
            $credentialService          = new CredentialService($this->clientIntegration);
            $apiKey                     = $credentialService->getShopifyApiKey()->getValue();
            $password                   = $credentialService->getShopifyPassword()->getValue();
            $hostName                   = $credentialService->getShopifyHostName()->getValue();

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