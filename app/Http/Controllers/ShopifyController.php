<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShoppingCarts\ShowIntegratedShoppingCart;
use App\Http\Requests\Shopify\DownloadShopifyProducts;
use App\Jobs\Shopify\Orders\DownloadShopifyOrdersJob;
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
use jamesvweston\Utilities\BooleanUtil;

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
        $shipped                            = BooleanUtil::getBooleanValue($request->input('shipped'));

        if ($shipped)
            $total                          = $this->shopifyService->getOrdersShippedCount();
        else
            $total                          = $this->shopifyService->getOrderImportCandidatesCount();

        $job                                = (new DownloadShopifyOrdersJob($this->integratedShoppingCart->getId(), $shipped))->onQueue('shopifyBulkImports');
        $this->dispatch($job);

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
        }
        else
            $total                          = $this->shopifyService->getProductImportCandidatesCount();

        $job                                = (new DownloadShopifyProductsJob($this->integratedShoppingCart->getId(), $downloadShopifyProducts->getPendingSku()))->onQueue('shopifyBulkImports');
        $this->dispatch($job);

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