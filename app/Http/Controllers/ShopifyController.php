<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\ShowIntegratedShoppingCart;
use App\Http\Requests\Shopify\DownloadShopifyProducts;
use App\Jobs\Shopify\Orders\ShopifyCreateOrderJob;
use App\Jobs\Shopify\Products\DownloadShopifyProductsJob;
use App\Models\Integrations\IntegratedShoppingCart;
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
     * @var IntegratedShoppingCart
     */
    private $integratedShoppingCart;

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
        $this->integratedShoppingCart      = $this->getIntegratedShoppingCartFromRoute($request->route('id'));
        $this->shopifyService               = new ShopifyService($this->integratedShoppingCart);
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
                $job                        = (new ShopifyCreateOrderJob(json_encode($shopifyOrder), $this->integratedShoppingCart->getId()))->onQueue('shopifyOrders');
                $this->dispatch($job);
            }
            usleep(250000);
        }
        $response   = [
            'total'                         => $total,
        ];
        return response($response);
    }


    public function downloadShippedOrders (Request $request)
    {
        $total                              = $this->shopifyService->getOrdersShippedCount();
        $totalPages                         = (int)ceil($total / 250);


        $this->shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        for ($page = 1; $page <= $totalPages; $page++)
        {
            set_time_limit(60);
            $shopifyOrdersResponse          = $this->shopifyService->getOrdersShipped($page, 250);
            $orderArray                     = json_decode($shopifyOrdersResponse, true);
            foreach ($orderArray AS $shopifyOrder)
            {
                $job                        = (new ShopifyCreateOrderJob(json_encode($shopifyOrder), $this->integratedShoppingCart->getId()))->onQueue('shopifyOrders');
                $this->dispatch($job);
            }
            usleep(250000);
        }

        $response   = [
            'total'                         => $total,
        ];
        return response($response);
    }


    public function downloadProducts (Request $request)
    {
        $downloadShopifyProducts            = new DownloadShopifyProducts($request->input());
        $downloadShopifyProducts->validate();
        $downloadShopifyProducts->clean();

        if ($downloadShopifyProducts->getPendingSku() == true)
        {
            $externalIdsResponse            = $this->orderItemRepo->getPendingExternalProductIds($this->integratedShoppingCart->getClient()->getId(), SourceUtility::SHOPIFY_ID);
            $total                          = sizeof($externalIdsResponse);

            $job                            = (new DownloadShopifyProductsJob($this->integratedShoppingCart->getId(), true, $downloadShopifyProducts->getImportVariantInventory()))->onQueue('shopifyBulkImports');
            $this->dispatch($job);
        }
        else
        {
            $total                          = $this->shopifyService->getProductImportCandidatesCount();

            $job                            = (new DownloadShopifyProductsJob($this->integratedShoppingCart->getId(), false, $downloadShopifyProducts->getImportVariantInventory()))->onQueue('shopifyBulkImports');
            $this->dispatch($job);
        }

        $response   = [
            'total'                         => $total,
        ];
        return response($response);
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