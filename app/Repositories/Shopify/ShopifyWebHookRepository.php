<?php

namespace App\Repositories\Shopify;


use jamesvweston\Shopify\Models\Requests\CreateShopifyWebHook;
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
        $address    = config('app.url') . '/webhooks/shopify/' . $this->integratedShoppingCart->getId() . '/' . $topic;
        $createShopifyWebHook->setAddress($address);

        $response                       = $this->shopifyClient->webHookApi->create($createShopifyWebHook);

        $integratedWebHook->setExternalId($response->getId());
        $integratedWebHook->setUrl($response->getAddress());
        return $integratedWebHook;
    }


    public function deleteWebHook ($id)
    {
        return $this->shopifyClient->webHookApi->delete($id);
    }
}