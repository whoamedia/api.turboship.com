<?php

namespace App\Repositories\Shopify;


use App\Integrations\Shopify\Models\Requests\CreateShopifyWebHook;
use App\Models\Integrations\IntegratedWebHook;

class ShopifyWebHookRepository extends BaseShopifyRepository
{


    /**
     * @param   IntegratedWebHook $integratedWebHook
     * @return  IntegratedWebHook
     */
    public function createWebHook (IntegratedWebHook $integratedWebHook)
    {
        $createShopifyWebHook           = new CreateShopifyWebHook();
        $createShopifyWebHook->setFormat('json');

        $topic                          = $integratedWebHook->getIntegrationWebHook()->getTopic();
        $createShopifyWebHook->setTopic($topic);

        //  config('app.url')       'https://dev-api.turboship.com'
        $address    = config('app.url') . '/webhooks/shopify/' . $this->clientIntegration->getId() . '/' . $topic;
        $createShopifyWebHook->setAddress($address);

        $response                       = $this->shopifyIntegration->webHookApi->create($createShopifyWebHook);

        $integratedWebHook->setExternalId($response->getId());
        return $integratedWebHook;
    }


    public function deleteWebHook ($id)
    {
        return $this->shopifyIntegration->webHookApi->delete($id);
    }
}