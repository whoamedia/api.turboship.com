<?php

namespace App\Services\Shopify\Mapping;


use jamesvweston\Shopify\Models\Responses\ShopifyProduct;
use jamesvweston\Shopify\Models\Responses\ShopifyProductImage;
use jamesvweston\Shopify\Models\Responses\ShopifyVariant;
use App\Models\CMS\Client;
use App\Models\OMS\Product;
use App\Models\OMS\ProductAlias;
use App\Models\OMS\Variant;
use App\Models\Support\Image;
use App\Repositories\Doctrine\OMS\ProductAliasRepository;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\Support\ImageRepository;
use EntityManager;

class ShopifyProductMappingService extends BaseShopifyMappingService
{

    /**
     * @var ImageRepository
     */
    protected $imageRepo;

    /**
     * @var ProductAliasRepository
     */
    protected $productAliasRepo;

    /**
     * @var VariantRepository
     */
    protected $variantRepo;


    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->imageRepo                    = EntityManager::getRepository('App\Models\Support\Image');
        $this->productAliasRepo             = EntityManager::getRepository('App\Models\OMS\ProductAlias');
        $this->variantRepo                  = EntityManager::getRepository('App\Models\OMS\Variant');
    }

    /**
     * @param   ShopifyProduct $shopifyProduct
     * @return  Product
     */
    public function handleMapping (ShopifyProduct $shopifyProduct)
    {
        $productAlias                       = $this->findLocalProductAlias($shopifyProduct);
        $productAlias                       = $this->toLocalProductAlias($shopifyProduct, $productAlias);

        $product                            = $this->toProduct($shopifyProduct, $productAlias->getProduct());
        if (is_null($productAlias->getId()))
            $product->addAlias($productAlias);

        foreach ($shopifyProduct->getVariants() AS $shopifyVariant)
        {
            $variant                        = $this->findLocalVariant($shopifyVariant);
            $variant                        = $this->toVariant($shopifyVariant, $variant);
            if (is_null($variant->getId()))
                $product->addVariant($variant);
        }

        foreach ($shopifyProduct->getImages() AS $shopifyProductImage)
        {
            $image                          = $this->findLocalImage($shopifyProductImage);
            $image                          = $this->toImage($shopifyProductImage, $image);
            if (is_null($image->getId()))
                $product->addImage($image);
        }

        return $product;
    }

    /**
     * @param   ShopifyProduct $shopifyProduct
     * @param   ProductAlias|null $productAlias
     * @return  ProductAlias
     */
    public function toLocalProductAlias (ShopifyProduct $shopifyProduct, ProductAlias $productAlias = null)
    {
        if (is_null($productAlias))
            $productAlias                   = new ProductAlias();

        $productAlias->setClient($this->client);
        $productAlias->setSource($this->shopifySource);
        $productAlias->setExternalId($shopifyProduct->getId());
        $productAlias->setExternalCreatedAt($this->toDate($shopifyProduct->getCreatedAt()));

        return $productAlias;
    }

    /**
     * Creates a Product from a ShopifyProduct
     * @param   ShopifyProduct $shopifyProduct
     * @param   Product $product
     * @return  Product
     */
    public function toProduct (ShopifyProduct $shopifyProduct, Product $product = null)
    {
        if (is_null($product))
            $product                        = new Product();

        $product->setName($shopifyProduct->getTitle());
        $product->setDescription($shopifyProduct->getBodyHtml());
        $product->setClient($this->client);

        return $product;
    }

    /**
     * Creates or updates a Variant
     * @param   ShopifyVariant $shopifyVariant
     * @param   Variant $variant
     * @return  Variant
     */
    public function toVariant (ShopifyVariant $shopifyVariant, Variant $variant = null)
    {
        if (is_null($variant))
            $variant                        = new Variant();

        $variant->setClient($this->client);
        $variant->setSource($this->shopifySource);
        $variant->setTitle($shopifyVariant->getTitle());
        $variant->setPrice($shopifyVariant->getPrice());
        $variant->setBarcode($shopifyVariant->getBarcode());
        $variant->setExternalId($shopifyVariant->getId());
        $variant->setExternalCreatedAt($this->toDate($shopifyVariant->getCreatedAt()));

        //  Convert grams to ounces
        $grams                              = $shopifyVariant->getGrams();
        if (is_null($grams) || empty($grams) || $grams < 0)
            $variant->setWeight(0.00);
        else
            $variant->setWeight($this->weightConversionService->gramsToOunces($grams));


        //  Lastly, handle the sku
        $variant->setOriginalSku($shopifyVariant->getSku());
        $sku                                = $this->shopifyMappingExceptionService->getShopifySku($this->client, $shopifyVariant->getSku(), $shopifyVariant->getId());
        $variant->setSku($sku);

        return $variant;
    }

    /**
     * @param   ShopifyProductImage $shopifyProductImage
     * @param   Image|null $image
     * @return  Image
     */
    public function toImage (ShopifyProductImage $shopifyProductImage, Image $image = null)
    {
        if (is_null($image))
            $image                          = new Image();

        $image->setSource($this->shopifySource);
        $image->setExternalId($shopifyProductImage->getId());
        $image->setExternalCreatedAt($this->toDate($shopifyProductImage->getCreatedAt()));
        $image->setPath($shopifyProductImage->getSrc());
        return $image;
    }

    /**
     * @param   ShopifyProduct $shopifyProduct
     * @return  ProductAlias|null
     */
    public function findLocalProductAlias (ShopifyProduct $shopifyProduct)
    {
        $productAliasQuery  = [
            'clientIds'             => $this->client->getId(),
            'sourceIds'             => $this->shopifySource->getId(),
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
    public function findLocalVariant (ShopifyVariant $shopifyVariant)
    {
        $variantQuery   = [
            'clientIds'             => $this->client->getId(),
            'sourceIds'             => $this->shopifySource->getId(),
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
    public function findLocalImage (ShopifyProductImage $shopifyProductImage)
    {
        $imageQuery     = [
            'sourceIds'             => $this->shopifySource->getId(),
            'externalIds'           => $shopifyProductImage->getId(),
        ];

        $imageResults               = $this->imageRepo->where($imageQuery);

        if (sizeof($imageResults) == 1)
            return $imageResults[0];
        else
            return null;
    }

    /**
     * Should we import the ShopifyProduct ?
     * @param   ShopifyProduct $shopifyProduct
     * @return  bool
     */
    public function shouldImport (ShopifyProduct $shopifyProduct)
    {
        if (is_null($shopifyProduct->getPublishedAt()))
            return false;
        else
            return true;
    }

}