<?php

namespace App\Services\Shopify;


use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Integrations\Shopify\Models\Responses\ShopifyProductImage;
use App\Integrations\Shopify\Models\Responses\ShopifyVariant;
use App\Models\CMS\Client;
use App\Models\Integrations\ClientECommerceIntegration;
use App\Models\OMS\ProductAlias;
use App\Models\OMS\Variant;
use App\Models\Support\Image;
use App\Repositories\Doctrine\OMS\ProductAliasRepository;
use App\Repositories\Doctrine\OMS\ProductRepository;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\Support\ImageRepository;
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
     * @var ClientECommerceIntegration
     */
    private $clientECommerceIntegration;

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

    /**
     * @var ImageRepository
     */
    private $imageRepo;


    public function __construct(ClientECommerceIntegration $clientECommerceIntegration)
    {
        $this->clientECommerceIntegration=$clientECommerceIntegration;
        $this->client                   = $clientECommerceIntegration->getClient();
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
        $this->productAliasRepo         = EntityManager::getRepository('App\Models\OMS\ProductAlias');
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->imageRepo                = EntityManager::getRepository('App\Models\Support\Image');
        $this->shopifyMappingService    = new ShopifyMappingService();
        $this->shopifyProductRepo       = new ShopifyProductRepository($this->clientECommerceIntegration);
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

                foreach ($shopifyProduct->getImages() AS $shopifyProductImage)
                {
                    $image              = $this->getProductImage($shopifyProductImage);
                    $image              = $this->shopifyMappingService->fromShopifyProductImage($shopifyProductImage, $image);

                    //  If the Image id is null that means it's new
                    if (is_null($image->getId()))
                        $product->addImage($image);
                }
                //  Try to validate. If it fails continue with the import
                try
                {
                    $productAlias->validate();
                }
                catch (\Exception $exception)
                {
                    echo 'ProductAlias failed to validate ' . $exception->getMessage() . PHP_EOL;
                    //  TODO: Log this. Do nothing and continue with import
                }

                //  If the ProductAlias id is null that means it's new
                if (is_null($productAlias->getId()))
                    $product->addAlias($productAlias);


                $shopifyVariantsResult  = $shopifyProduct->getVariants();
                foreach ($shopifyVariantsResult AS $shopifyVariant)
                {
                    //  If the Variant sku isn't set we shouldn't import it
                    if (is_null($shopifyVariant->getSku()) || empty(trim($shopifyVariant->getSku())))
                    {
                        echo 'Variant sku is empty' . PHP_EOL;
                        continue;
                    }

                    $variant            = $this->getVariant($shopifyVariant);
                    $variant            = $this->shopifyMappingService->fromShopifyVariant($product, $shopifyVariant, $variant);

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
                        echo 'Variant failed to validate ' . $variant->getExternalId() . '   '. $exception->getMessage() . PHP_EOL;
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

    /**
     * @param   ShopifyProductImage $shopifyProductImage
     * @return  Image|null
     */
    public function getProductImage (ShopifyProductImage $shopifyProductImage)
    {
        $imageQuery     = [
            'crmSourceIds'          => CRMSourceUtility::SHOPIFY_ID,
            'externalIds'           => $shopifyProductImage->getId(),
        ];

        $imageResults               = $this->imageRepo->where($imageQuery);

        if (sizeof($imageResults) == 1)
            return $imageResults[0];
        else
            return null;
    }

}