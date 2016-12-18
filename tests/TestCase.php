<?php

namespace Tests;


use App\Models\CMS\Validation\ClientValidation;
use App\Services\Shopify\ShopifyConfiguration;
use App\Services\Shopify\ShopifyService;
use App\Utilities\CredentialUtility;
use EntityManager;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var ClientValidation
     */
    protected $clientValidation;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $this->clientValidation         = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));

        return $app;
    }

    /**
     * @return ShopifyService
     */
    protected function getShopifyService ()
    {
        $client                         = $this->clientValidation->idExists(1);

        $credentialUtility              = new CredentialUtility($client);
        $apiKey                         = $credentialUtility->getShopifyApiKey()->getValue();
        $password                       = $credentialUtility->getShopifyPassword()->getValue();
        $hostName                       = $credentialUtility->getShopifyHostName()->getValue();

        $shopifyConfiguration           = new ShopifyConfiguration();
        $shopifyConfiguration->setApiKey($apiKey);
        $shopifyConfiguration->setPassword($password);
        $shopifyConfiguration->setHostName($hostName);

        $shopifyService                 = new ShopifyService($shopifyConfiguration);
        return $shopifyService;
    }
}
