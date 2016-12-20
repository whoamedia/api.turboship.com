<?php

namespace App\Services\Shopify;


use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Models\CMS\Client;
use App\Models\Integrations\ClientIntegration;
use App\Models\OMS\Order;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Shopify\ShopifyOrderRepository;
use App\Services\Order\OrderApprovalService;
use App\Utilities\CRMSourceUtility;
use EntityManager;

class ShopifyOrderService
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ClientIntegration
     */
    private $clientIntegration;

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


    public function __construct(ClientIntegration $clientIntegration)
    {
        $this->clientIntegration        = $clientIntegration;
        $this->client                   = $clientIntegration->getClient();
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->shopifyMappingService    = new ShopifyMappingService();
        $this->orderApprovalService     = new OrderApprovalService();
        $this->shopifyOrderRepo         = new ShopifyOrderRepository($this->clientIntegration);
    }

    public function downloadOrders ()
    {
        $orderQuery     = [
            'clientIds'                 => $this->client->getId(),
            'crmSourceIds'              => CRMSourceUtility::SHOPIFY_ID,
        ];

        $maxExternalId                  = $this->orderRepo->getMaxExternalId($orderQuery);

        $totalCandidates                = $this->shopifyOrderRepo->getImportCandidatesCount($maxExternalId);

        $limit                          = 250;
        $totalPages                     = (int)ceil($totalCandidates / $limit);

        for ($page = 1; $page <= $totalPages; $page++)
        {
            $shopifyOrdersResponse          = $this->shopifyOrderRepo->getImportCandidates($page, $limit, $maxExternalId);

            foreach ($shopifyOrdersResponse AS $shopifyOrder)
            {
                $order                      = $this->getOrder($shopifyOrder);

                //  We found a match and do not need to do an import
                if (!is_null($order))
                    continue;
                else
                {
                    $order                  = $this->shopifyMappingService->fromShopifyOrder($this->client, $shopifyOrder, $order);

                    foreach ($shopifyOrder->getLineItems() AS $shopifyOrderLineItem)
                    {
                        $orderItem                  = $this->shopifyMappingService->fromShopifyOrderLineItem($shopifyOrderLineItem);
                        $order->addItem($orderItem);
                    }

                    $this->orderRepo->saveAndCommit($order);
                    $this->orderApprovalService->processOrder($order);
                    $this->orderRepo->saveAndCommit($order);
                }
            }
        }
    }

    /**
     * @param   ShopifyOrder $shopifyOrder
     * @return  Order|null
     */
    public function getOrder (ShopifyOrder $shopifyOrder)
    {
        $orderQuery     = [
            'clientIds'             => $this->client->getId(),
            'crmSourceIds'          => CRMSourceUtility::SHOPIFY_ID,
            'externalIds'           => $shopifyOrder->getId(),
        ];

        $orderResult                = $this->orderRepo->where($orderQuery);

        //  We found a match and do not need to do an import
        if (sizeof($orderResult) == 1)
            return $orderResult[0];
        else
            return null;
    }

}