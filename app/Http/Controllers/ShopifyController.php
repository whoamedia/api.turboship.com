<?php

namespace App\Http\Controllers;


use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Shopify\ShopifyOrderRepository;
use App\Services\Order\OrderApprovalService;
use App\Services\Shopify\Mapping\ShopifyOrderMappingService;
use Illuminate\Http\Request;
use EntityManager;

class ShopifyController extends BaseIntegratedServiceController
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var OrderApprovalService
     */
    private $orderApprovalService;


    public function __construct()
    {
        parent::__construct();

        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderApprovalService         = new OrderApprovalService();
    }

    public function downloadOrders (Request $request)
    {
        $shoppingCartIntegration        = parent::getIntegratedShoppingCart($request->route('id'));
        $shopifyOrderRepository         = new ShopifyOrderRepository($shoppingCartIntegration);
        $shopifyOrderMappingService     = new ShopifyOrderMappingService($shoppingCartIntegration->getClient());

        $shopifyOrdersResponse          = $shopifyOrderRepository->getImportCandidates();
        foreach ($shopifyOrdersResponse AS $shopifyOrder)
        {
            if (!$shopifyOrderMappingService->shouldImportOrder($shopifyOrder))
                continue;

            $order                          = $shopifyOrderMappingService->handleMapping($shopifyOrder);
            $this->orderApprovalService->processOrder($order);
            $this->orderRepo->saveAndCommit($order);
        }
    }
}