<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\ShowIntegratedShoppingCart;
use App\Http\Requests\Shopify\DownloadShopifyProducts;
use App\Jobs\Shopify\Orders\ShopifyCreateOrderJob;
use App\Jobs\Shopify\Products\ShopifyCreateProductJob;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Integrations\Validation\IntegratedShoppingCartValidation;
use App\Repositories\Doctrine\OMS\OrderItemRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Repositories\Shopify\ShopifyOrderRepository;
use App\Repositories\Shopify\ShopifyProductRepository;
use App\Services\Order\OrderApprovalService;
use App\Utilities\SourceUtility;
use Illuminate\Http\Request;
use EntityManager;

class ShopifyController extends BaseAuthController
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
        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderItemRepo                = EntityManager::getRepository('App\Models\OMS\OrderItem');
        $this->orderApprovalService         = new OrderApprovalService();
        $this->productRepo                  = EntityManager::getRepository('App\Models\OMS\Product');
    }

    public function downloadOrders (Request $request)
    {
        $shoppingCartIntegration        = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        $shopifyOrderRepository         = new ShopifyOrderRepository($shoppingCartIntegration);

        $total                          = $shopifyOrderRepository->getImportCandidatesCount();
        $totalPages                     = (int)ceil($total / 250);

        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++)
        {
            set_time_limit(60);
            $shopifyOrdersResponse          = $shopifyOrderRepository->getImportCandidates($currentPage, 250);
            foreach ($shopifyOrdersResponse AS $shopifyOrder)
            {
                $job                        = (new ShopifyCreateOrderJob($shopifyOrder, $shoppingCartIntegration->getId()))->onQueue('shopifyOrders');
                $this->dispatch($job);
            }
            usleep(250000);
        }
    }


    public function downloadProducts (Request $request)
    {
        $shoppingCartIntegration        = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        $shopifyProductRepo             = new ShopifyProductRepository($shoppingCartIntegration);
        $downloadShopifyProducts        = new DownloadShopifyProducts($request->input());

        if ($downloadShopifyProducts->getPendingSku() == true)
        {
            $externalIdsResponse        = $this->orderItemRepo->getPendingExternalProductIds($shoppingCartIntegration->getClient()->getId(), SourceUtility::SHOPIFY_ID);
            $maxIds                     = 20;
            for ($i = 0; $i < sizeof($externalIdsResponse); $i+=$maxIds)
            {
                set_time_limit(30);
                $externalIds            = array_slice($externalIdsResponse, $i, $maxIds);
                $externalIds            = implode(',', $externalIds);

                $shopifyProductsResponse    = $shopifyProductRepo->getImportCandidates(1, 250, $externalIds);
                foreach ($shopifyProductsResponse AS $shopifyProduct)
                {
                    $job                        = (new ShopifyCreateProductJob($shopifyProduct, $shoppingCartIntegration->getId()))->onQueue('shopifyProducts');
                    $this->dispatch($job);
                }
                usleep(250000);
            }
        }
        else
        {
            $total                      = $shopifyProductRepo->getImportCandidatesCount();
            $totalPages                 = (int)ceil($total / 250);

            for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++)
            {
                set_time_limit(60);
                $shopifyProductsResponse    = $shopifyProductRepo->getImportCandidates($currentPage, 250);
                foreach ($shopifyProductsResponse AS $shopifyProduct)
                {
                    $job                        = (new ShopifyCreateProductJob($shopifyProduct, $shoppingCartIntegration->getId()))->onQueue('shopifyProducts');
                    $this->dispatch($job);
                }
                usleep(250000);
            }
        }
    }

    /**
     * @param   int     $id
     * @return  IntegratedShoppingCart
     */
    private function getIntegratedShoppingCartFromRoute ($id)
    {
        $showIntegratedShoppingCart         = new ShowIntegratedShoppingCart();
        $showIntegratedShoppingCart->setId($id);
        $showIntegratedShoppingCart->validate();
        $showIntegratedShoppingCart->clean();

        $integratedShoppingCartValidation   = new IntegratedShoppingCartValidation();
        return $integratedShoppingCartValidation->idExists($showIntegratedShoppingCart->getId());
    }
}