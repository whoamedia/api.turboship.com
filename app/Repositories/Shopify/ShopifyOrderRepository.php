<?php

namespace App\Repositories\Shopify;


use App\Integrations\Shopify\Models\Requests\GetShopifyOrderCount;
use App\Integrations\Shopify\Models\Requests\GetShopifyOrders;
use App\Integrations\Shopify\Models\Responses\ShopifyOrder;

class ShopifyOrderRepository extends BaseShopifyRepository
{

    /**
     * @param   int             $page
     * @param   int             $limit
     * @param   string|null     $sinceId
     * @return  \App\Integrations\Shopify\Models\Responses\ShopifyOrder[]
     */
    public function getImportCandidates ($page = 1, $limit = 250, $sinceId = null)
    {
        $getShopifyOrders               = new GetShopifyOrders();
        $getShopifyOrders->setFulfillmentStatus('unshipped');
        $getShopifyOrders->setFinancialStatus('paid');
        $getShopifyOrders->setTest(false);

        if (!is_null($sinceId))
            $getShopifyOrders->setSinceId($sinceId);

        $getShopifyOrders->setOrder('created_at asc');
        $getShopifyOrders->setPage($page);
        $getShopifyOrders->setLimit($limit);

        $shopifyOrdersResponse          = $this->shopifyIntegration->orderApi->get($getShopifyOrders);
        return $shopifyOrdersResponse;
    }

    /**
     * @param   string|null     $sinceId
     * @return  int
     */
    public function getImportCandidatesCount ($sinceId = null)
    {
        $getShopifyOrderCount           = new GetShopifyOrderCount();
        $getShopifyOrderCount->setFulfillmentStatus('unshipped');
        $getShopifyOrderCount->setFinancialStatus('paid');
        $getShopifyOrderCount->setTest(false);

        if (!is_null($sinceId))
            $getShopifyOrderCount->setSinceId($sinceId);

        $shopifyOrderCountResponse      = $this->shopifyIntegration->orderApi->count($getShopifyOrderCount);
        return $shopifyOrderCountResponse;
    }

}