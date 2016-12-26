<?php

namespace App\Repositories\Shopify;


use App\Models\CMS\Client;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\Integrations\IntegratedShoppingCart;
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
     * @param ShopifyIntegration|null       $shopifyIntegration
     */
    public function __construct(IntegratedShoppingCart $integratedShoppingCart, $shopifyIntegration = null)
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->clientValidation         = new ClientValidation($this->clientRepo);
        $this->integratedShoppingCart        = $integratedShoppingCart;
        $this->client                   = $this->integratedShoppingCart->getClient();

        if (!is_null($shopifyIntegration))
            $this->shopifyIntegration   = $shopifyIntegration;
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

            $this->shopifyIntegration   = new ShopifyIntegration($shopifyConfiguration);
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