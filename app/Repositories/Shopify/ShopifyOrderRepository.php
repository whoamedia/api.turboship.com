<?php

namespace App\Repositories\Shopify;


use App\Integrations\Shopify\Models\Requests\GetShopifyOrders;

class ShopifyOrderRepository extends BaseShopifyRepository
{
    /**
     * @return \App\Integrations\Shopify\Models\Responses\ShopifyOrder[]
     */
    public function getImportCandidates ()
    {
        $getShopifyOrders               = new GetShopifyOrders();
        $getShopifyOrders->setFulfillmentStatus('unshipped');
        $getShopifyOrders->setFinancialStatus('paid');
        $getShopifyOrders->setTest(false);
        $getShopifyOrders->setPage(1);
        $getShopifyOrders->setLimit(250);

        $shopifyOrdersResponse          = $this->shopifyIntegration->orderApi->get($getShopifyOrders);
        return $shopifyOrdersResponse;
    }
}