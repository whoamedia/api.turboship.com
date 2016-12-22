<?php

namespace Tests;


use App\Models\CMS\Validation\ClientValidation;
use App\Integrations\Shopify\ShopifyConfiguration;
use App\Integrations\Shopify\ShopifyIntegration;
use App\Models\Integrations\ClientIntegration;
use App\Repositories\Doctrine\Integrations\ClientIntegrationRepository;
use App\Utilities\CredentialUtility;
use EntityManager;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var ClientIntegration
     */
    protected $clientIntegration;

    /**
     * @var ClientIntegrationRepository
     */
    protected $clientIntegrationRepo;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $this->clientIntegrationRepo    = EntityManager::getRepository('App\Models\Integrations\ClientIntegration');

        return $app;
    }

    /**
     * @return ShopifyIntegration
     */
    protected function getShopifyIntegration ()
    {
        $this->clientIntegration        = $this->clientIntegrationRepo->getOneById(1);

        $credentialUtility              = new CredentialUtility($this->clientIntegration);
        $apiKey                         = $credentialUtility->getShopifyApiKey()->getValue();
        $password                       = $credentialUtility->getShopifyPassword()->getValue();
        $hostName                       = $credentialUtility->getShopifyHostName()->getValue();

        $shopifyConfiguration           = new ShopifyConfiguration();
        $shopifyConfiguration->setApiKey($apiKey);
        $shopifyConfiguration->setPassword($password);
        $shopifyConfiguration->setHostName($hostName);

        $shopifyIntegration             = new ShopifyIntegration($shopifyConfiguration);
        return $shopifyIntegration;
    }
}
