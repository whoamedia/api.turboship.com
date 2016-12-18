<?php

namespace App\Repositories\Shopify;


use App\Models\CMS\Client;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderApprovalService;
use App\Services\Shopify\Models\Requests\GetShopifyOrders;
use App\Services\ShopifyMappingService;
use App\Utilities\OrderSourceUtility;
use EntityManager;

class ShopifyOrderRepository extends BaseShopifyRepository
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var ShopifyMappingService
     */
    private $shopifyMappingService;

    /**
     * @var OrderApprovalService
     */
    private $orderApprovalService;

    public function __construct(Client $client, $shopifyService = null)
    {
        parent::__construct($client, $shopifyService);

        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->shopifyMappingService    = new ShopifyMappingService();
        $this->orderApprovalService     = new OrderApprovalService();
    }

    public function downloadOrders ()
    {
        $shopifyOrdersResponse          = $this->getCandidateImportOrders();
        foreach ($shopifyOrdersResponse AS $shopifyOrder)
        {
            $orderQuery     = [
                'clientIds'             => $this->client->getId(),
                'sourceIds'             => OrderSourceUtility::SHOPIFY_ID,
                'externalIds'           => $shopifyOrder->getId(),
            ];

            $orderResult                = $this->orderRepo->where($orderQuery);

            //  We found a match and do not need to do an import
            if (sizeof($orderResult) == 1)
                continue;
            else
            {
                $order                  = $this->shopifyMappingService->fromShopifyOrder($this->client, $shopifyOrder);
                $this->orderRepo->saveAndCommit($order);
                $this->orderApprovalService->processOrder($order);
                $this->orderRepo->saveAndCommit($order);
            }
        }
    }

    /**
     * @return \App\Services\Shopify\Models\Responses\ShopifyOrder[]
     */
    public function getCandidateImportOrders ()
    {
        $getShopifyOrders               = new GetShopifyOrders();
        $getShopifyOrders->setFulfillmentStatus('unshipped');
        $getShopifyOrders->setFinancialStatus('paid');
        $getShopifyOrders->setPage(1);
        $getShopifyOrders->setLimit(250);

        $shopifyOrdersResponse          = $this->shopifyService->orderApi->get($getShopifyOrders);
        return $shopifyOrdersResponse;
    }
}