<?php

namespace App\Repositories\Shopify;


use App\Integrations\Shopify\Models\Requests\CreateShopifyWebHook;
use App\Models\Integrations\ClientWebHook;

class ShopifyWebHookRepository extends BaseShopifyRepository
{


    /**
     * @param   ClientWebHook $clientWebHook
     * @return  ClientWebHook
     */
    public function createWebHook (ClientWebHook $clientWebHook)
    {
        $createShopifyWebHook           = new CreateShopifyWebHook();
        $createShopifyWebHook->setFormat('json');

        $topic                          = $clientWebHook->getIntegrationWebHook()->getTopic();
        $createShopifyWebHook->setTopic($topic);

        $address    = config('app.url') . '/webhooks/shopify/' . $this->clientIntegration->getId() . '/' . $topic;
        $createShopifyWebHook->setAddress($address);

        $response                       = $this->shopifyIntegration->webHookApi->create($createShopifyWebHook);

        $clientWebHook->setExternalId($response->getId());
        return $clientWebHook;
    }


    public function deleteWebHook ($id)
    {
        return $this->shopifyIntegration->webHookApi->delete($id);
    }
}