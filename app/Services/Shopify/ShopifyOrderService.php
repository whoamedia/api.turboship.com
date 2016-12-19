<?php

namespace App\Services\Shopify;


use App\Models\CMS\Client;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Shopify\ShopifyOrderRepository;
use App\Services\Order\OrderApprovalService;
use App\Services\Shopify\ShopifyMappingService;
use App\Utilities\OrderSourceUtility;
use EntityManager;

class ShopifyOrderService
{

    /**
     * @var Client
     */
    private $client;

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

    /**
     * @var ShopifyOrderRepository
     */
    private $shopifyOrderRepo;


    public function __construct(Client $client)
    {
        $this->client                   = $client;
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->shopifyMappingService    = new ShopifyMappingService();
        $this->orderApprovalService     = new OrderApprovalService();
        $this->shopifyOrderRepo         = new ShopifyOrderRepository($this->client);
    }

    public function downloadOrders ()
    {
        $shopifyOrdersResponse          = $this->shopifyOrderRepo->getImportCandidates();
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

}