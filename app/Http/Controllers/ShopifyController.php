<?php

namespace App\Http\Controllers;


use App\Http\Requests\Shopify\DownloadShopifyProducts;
use App\Repositories\Doctrine\OMS\OrderItemRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Repositories\Shopify\ShopifyOrderRepository;
use App\Repositories\Shopify\ShopifyProductRepository;
use App\Services\Order\OrderApprovalService;
use App\Services\Shopify\Mapping\ShopifyOrderMappingService;
use App\Services\Shopify\Mapping\ShopifyProductMappingService;
use App\Utilities\CRMSourceUtility;
use App\Utilities\OrderStatusUtility;
use Illuminate\Http\Request;
use EntityManager;

class ShopifyController extends BaseIntegratedServiceController
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var OrderItemRepository
     */
    private $orderItemRepo;

    /**
     * @var OrderApprovalService
     */
    private $orderApprovalService;

    /**
     * @var ProductRepository
     */
    private $productRepo;


    public function __construct()
    {
        parent::__construct();

        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderItemRepo                = EntityManager::getRepository('App\Models\OMS\OrderItem');
        $this->orderApprovalService         = new OrderApprovalService();
        $this->productRepo                  = EntityManager::getRepository('App\Models\OMS\Product');
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


    public function downloadProducts (Request $request)
    {
        $shoppingCartIntegration        = parent::getIntegratedShoppingCart($request->route('id'));
        $shopifyProductRepo             = new ShopifyProductRepository($shoppingCartIntegration);
        $shopifyProductMappingService   = new ShopifyProductMappingService($shoppingCartIntegration->getClient());
        $downloadShopifyProducts        = new DownloadShopifyProducts($request->input());

        if ($downloadShopifyProducts->getPendingSku() == true)
        {
            $externalIdsResponse        = $this->orderItemRepo->getPendingExternalProductIds($shoppingCartIntegration->getClient()->getId(), CRMSourceUtility::SHOPIFY_ID);
            $maxIds                     = 10;
            for ($i = 0; $i < sizeof($externalIdsResponse); $i+=$maxIds)
            {
                set_time_limit(5);
                $externalIds            = array_slice($externalIdsResponse, $i, $maxIds);
                $externalIds            = implode(',', $externalIds);

                $shopifyProductsResponse    = $shopifyProductRepo->getImportCandidates(1, 250, $externalIds);
                foreach ($shopifyProductsResponse AS $shopifyProduct)
                {
                    if (!$shopifyProductMappingService->shouldImport($shopifyProduct))
                        continue;
                    $product                = $shopifyProductMappingService->handleMapping($shopifyProduct);
                    $this->productRepo->saveAndCommit($product);
                }
                usleep(250000);
            }
        }
        else
        {
            $shopifyProductsResponse    = $shopifyProductRepo->getImportCandidates(1, 250);
            foreach ($shopifyProductsResponse AS $shopifyProduct)
            {
                if (!$shopifyProductMappingService->shouldImport($shopifyProduct))
                    continue;
                $product                = $shopifyProductMappingService->handleMapping($shopifyProduct);
                $this->productRepo->saveAndCommit($product);
            }
        }

    }
}