<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\ShowIntegratedShoppingCart;
use App\Http\Requests\Shopify\DownloadShopifyProducts;
use App\Jobs\Shopify\Orders\ShopifyCreateOrderJob;
use App\Jobs\Shopify\Products\ShopifyCreateProductJob;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Integrations\ShoppingCartIntegration;
use App\Models\Integrations\Validation\IntegratedShoppingCartValidation;
use App\Repositories\Doctrine\OMS\OrderItemRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Services\Order\OrderApprovalService;
use App\Services\Shopify\ShopifyService;
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

    /**
     * @var ShoppingCartIntegration
     */
    private $shoppingCartIntegration;

    /**
     * @var ShopifyService
     */
    private $shopifyService;


    public function __construct(Request $request)
    {
        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderItemRepo                = EntityManager::getRepository('App\Models\OMS\OrderItem');
        $this->orderApprovalService         = new OrderApprovalService();
        $this->productRepo                  = EntityManager::getRepository('App\Models\OMS\Product');
        $this->shoppingCartIntegration      = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        $this->shopifyService               = new ShopifyService($this->shoppingCartIntegration);
    }

    public function downloadOrders (Request $request)
    {
        $total                              = $this->shopifyService->getOrderImportCandidatesCount();
        $totalPages                         = (int)ceil($total / 250);

        $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++)
        {
            set_time_limit(60);
            $shopifyOrdersResponse          = $this->shopifyService->getOrderImportCandidates($currentPage, 250);
            $orderArray                     = json_decode($shopifyOrdersResponse, true);
            foreach ($orderArray AS $shopifyOrder)
            {
                $job                        = (new ShopifyCreateOrderJob(json_encode($shopifyOrder), $this->shoppingCartIntegration->getId()))->onQueue('shopifyOrders');
                $this->dispatch($job);
            }
            usleep(250000);
        }
    }


    public function downloadShippedOrders (Request $request)
    {
        $total                              = $this->shopifyService->getOrdersShippedCount();
        $totalPages                         = (int)ceil($total / 250);

        $currentPage                        = is_null($request->input('currentPage')) ? 1 : intval($request->input('currentPage'));
        $maxPages                           = is_null($request->input('maxPages')) ? 100 : intval($request->input('maxPages'));

        $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        $maxPageCount                       = 0;
        for ($page = $currentPage; $page <= $totalPages; $page++)
        {
            set_time_limit(60);
            $shopifyOrdersResponse          = $this->shopifyService->getOrdersShipped($page, 250);
            $orderArray                     = json_decode($shopifyOrdersResponse, true);
            foreach ($orderArray AS $shopifyOrder)
            {
                $job                        = (new ShopifyCreateOrderJob(json_encode($shopifyOrder), $this->shoppingCartIntegration->getId()))->onQueue('shopifyOrders');
                $this->dispatch($job);
            }
            usleep(250000);

            $maxPageCount++;

            if ($maxPages == $maxPageCount)
                return response('maxPages met');
        }
    }


    public function downloadProducts (Request $request)
    {
        $downloadShopifyProducts            = new DownloadShopifyProducts($request->input());

        if ($downloadShopifyProducts->getPendingSku() == true)
        {
            $externalIdsResponse            = $this->orderItemRepo->getPendingExternalProductIds($this->shoppingCartIntegration->getClient()->getId(), SourceUtility::SHOPIFY_ID);
            $maxIds                         = 20;
            $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
            for ($i = 0; $i < sizeof($externalIdsResponse); $i+=$maxIds)
            {
                set_time_limit(30);
                $externalIds                = array_slice($externalIdsResponse, $i, $maxIds);
                $externalIds                = implode(',', $externalIds);

                $shopifyProductsResponse    = $this->shopifyService->getProductImportCandidates(1, 250, $externalIds);
                $productArray               = json_decode($shopifyProductsResponse, true);
                foreach ($productArray AS $shopifyProduct)
                {
                    $job                    = (new ShopifyCreateProductJob(json_encode($shopifyProduct), $this->shoppingCartIntegration->getId()))->onQueue('shopifyProducts');
                    $this->dispatch($job);
                }
                usleep(250000);
            }
        }
        else
        {
            $total                          = $this->shopifyService->getProductImportCandidatesCount();
            $totalPages                     = (int)ceil($total / 250);

            $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
            for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++)
            {
                set_time_limit(60);
                $shopifyProductsResponse    = $this->shopifyService->getProductImportCandidates($currentPage, 250);
                $productArray               = json_decode($shopifyProductsResponse, true);
                foreach ($productArray AS $shopifyProduct)
                {
                    $job                    = (new ShopifyCreateProductJob(json_encode($shopifyProduct), $this->shoppingCartIntegration->getId()))->onQueue('shopifyProducts');
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