<?php

namespace Tests;

use App\Services\Shopify\ShopifyConfiguration;
use App\Services\Shopify\ShopifyService;
use App\Utilities\CredentialUtility;

class ShopifyApiTest extends TestCase
{


    public function testIntegration ()
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

        $products                       = $shopifyService->productApi->get();

        $count                          = $shopifyService->productApi->count();
        dd($count);
    }
}
