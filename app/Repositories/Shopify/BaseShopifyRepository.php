<?php

namespace App\Repositories\Shopify;


use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Services\Shopify\ShopifyConfiguration;
use App\Services\Shopify\ShopifyService;
use App\Utilities\CredentialUtility;
use EntityManager;

class BaseShopifyRepository
{

    /**
     * @var ShopifyService
     */
    protected $shopifyService;

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
     * @param ShopifyService|null       $shopifyService
     */
    public function __construct(Client $client, $shopifyService = null)
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->clientValidation         = new ClientValidation($this->clientRepo);
        $this->client                   = $client;

        if (!is_null($shopifyService))
            $this->shopifyService       = $shopifyService;
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

            $this->shopifyService       = new ShopifyService($shopifyConfiguration);
        }
    }

    /**
     * @return ShopifyService
     */
    public function getShopifyService()
    {
        return $this->shopifyService;
    }

    /**
     * @param ShopifyService $shopifyService
     */
    public function setShopifyService($shopifyService)
    {
        $this->shopifyService = $shopifyService;
    }

}