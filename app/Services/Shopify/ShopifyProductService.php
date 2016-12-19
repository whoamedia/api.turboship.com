<?php

namespace App\Services\Shopify;


use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Integrations\Shopify\Models\Responses\ShopifyVariant;
use App\Models\CMS\Client;
use App\Models\OMS\ProductAlias;
use App\Models\OMS\Variant;
use App\Repositories\Doctrine\OMS\ProductAliasRepository;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Shopify\ShopifyProductRepository;
use App\Utilities\CRMSourceUtility;
use EntityManager;

class ShopifyProductService
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * @var ProductAliasRepository
     */
    private $productAliasRepo;

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var ShopifyProductRepository
     */
    private $shopifyProductRepo;


    public function __construct(Client $client)
    {
        $this->client                   = $client;
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
        $this->productAliasRepo         = EntityManager::getRepository('App\Models\OMS\ProductAlias');
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->shopifyMappingService    = new ShopifyMappingService();
        $this->shopifyProductRepo       = new ShopifyProductRepository($this->client);
    }


    public function download ()
    {
        $totalCandidates                = $this->shopifyProductRepo->getImportCandidatesCount();
        $limit                          = 250;
        $totalPages                     = (int)ceil($totalCandidates / $limit);

        for ($page = 1; $page <= $totalPages; $page++)
        {
            $shopifyProductResponse     = $this->shopifyProductRepo->getImportCandidates($page, $limit);
            foreach ($shopifyProductResponse AS $shopifyProduct)
            {
                $productAlias           = $this->getProductAlias($shopifyProduct);
                $productAlias           = $this->shopifyMappingService->toLocalProductAlias($this->client, $shopifyProduct, $productAlias);

                $product                = $this->shopifyMappingService->fromShopifyProduct($this->client, $shopifyProduct, $productAlias->getProduct());

                //  Try to validate. If it fails continue with the import
                try
                {
                    $productAlias->validate();
                }
                catch (\Exception $exception)
                {
                    //  TODO: Log this. Do nothing and continue with import
                }

                //  If the ProductAlias id is null that means it's new
                if (is_null($productAlias->getId()))
                    $product->addAlias($productAlias);


                $shopifyVariantsResult  = $shopifyProduct->getVariants();
                foreach ($shopifyVariantsResult AS $shopifyVariant)
                {
                    $variant            = $this->getVariant($shopifyVariant);
                    $variant            = $this->shopifyMappingService->fromShopifyVariant($product, $shopifyVariant);

                    //  If the Variant id is null that means it's new
                    if (is_null($variant->getId()))
                        $product->addVariant($variant);

                    //  Try to validate. If it fails continue with the import
                    try
                    {
                        $variant->validate();
                    }
                    catch (\Exception $exception)
                    {
                        //  TODO: Log this
                        $product->removeVariant($variant);
                    }

                }

                $this->productRepo->saveAndCommit($product);
            }
        }

    }

    /**
     * @param   ShopifyProduct $shopifyProduct
     * @return  ProductAlias|null
     */
    public function getProductAlias (ShopifyProduct $shopifyProduct)
    {
        $productAliasQuery  = [
            'clientIds'             => $this->client->getId(),
            'crmSourceIds'          => CRMSourceUtility::SHOPIFY_ID,
            'externalIds'           => $shopifyProduct->getId(),
        ];

        $productAliasResult         = $this->productAliasRepo->where($productAliasQuery);

        if (sizeof($productAliasResult) == 1)
            return $productAliasResult[0];
        else
            return null;
    }

    /**
     * @param   ShopifyVariant $shopifyVariant
     * @return  Variant|null
     */
    public function getVariant (ShopifyVariant $shopifyVariant)
    {
        $variantQuery   = [
            'clientIds'             => $this->client->getId(),
            'crmSourceIds'          => CRMSourceUtility::SHOPIFY_ID,
            'externalIds'           => $shopifyVariant->getId(),
        ];

        $variantResult              = $this->variantRepo->where($variantQuery);

        if (sizeof($variantResult) == 1)
            return $variantResult[0];
        else
            return null;
    }

}