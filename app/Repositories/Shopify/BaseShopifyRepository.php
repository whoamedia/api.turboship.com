<?php

namespace App\Repositories\Shopify;


use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Repositories\Doctrine\CMS\ClientRepository;
use jamesvweston\Shopify\ShopifyConfiguration;
use jamesvweston\Shopify\ShopifyClient;
use App\Services\CredentialService;
use EntityManager;

class BaseShopifyRepository
{

    /**
     * @var ShopifyClient
     */
    protected $shopifyClient;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var IntegratedShoppingCart
     */
    protected $integratedShoppingCart;

    /**
     * @var ClientRepository
     */
    protected $clientRepo;


    /**
     * BaseShopifyRepository constructor.
     * @param IntegratedShoppingCart        $integratedShoppingCart
     * @param ShopifyClient|null       $shopifyClient
     */
    public function __construct(IntegratedShoppingCart $integratedShoppingCart, $shopifyClient = null)
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->clientValidation         = new ClientValidation($this->clientRepo);
        $this->integratedShoppingCart   = $integratedShoppingCart;
        $this->client                   = $this->integratedShoppingCart->getClient();

        if (!is_null($shopifyClient))
            $this->shopifyClient   = $shopifyClient;
        else
        {
            $credentialService          = new CredentialService($this->integratedShoppingCart);
            $apiKey                     = $credentialService->getShopifyApiKey()->getValue();
            $password                   = $credentialService->getShopifyPassword()->getValue();
            $hostName                   = $credentialService->getShopifyHostName()->getValue();

            $shopifyConfiguration       = new ShopifyConfiguration();
            $shopifyConfiguration->setApiKey($apiKey);
            $shopifyConfiguration->setPassword($password);
            $shopifyConfiguration->setHostName($hostName);

            $this->shopifyClient   = new ShopifyClient($shopifyConfiguration);
        }
    }

    /**
     * @return ShopifyClient
     */
    public function getShopifyClient()
    {
        return $this->shopifyClient;
    }

    /**
     * @param ShopifyClient $shopifyClient
     */
    public function setShopifyClient($shopifyClient)
    {
        $this->shopifyClient = $shopifyClient;
    }

}