<?php

namespace App\Services\Shopify;


use App\Integrations\Shopify\Models\Responses\ShopifyProduct;
use App\Integrations\Shopify\Models\Responses\ShopifyProductImage;
use App\Integrations\Shopify\Models\Responses\ShopifyVariant;
use App\Models\CMS\Client;
use App\Models\Locations\Address;
use App\Models\OMS\Order;
use App\Models\OMS\OrderItem;
use App\Models\OMS\CRMSource;
use App\Models\OMS\Product;
use App\Models\OMS\ProductAlias;
use App\Models\OMS\Validation\CRMSourceValidation;
use App\Integrations\Shopify\Models\Responses\ShopifyAddress;
use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Integrations\Shopify\Models\Responses\ShopifyOrderLineItem;
use App\Models\OMS\Variant;
use App\Models\Support\Image;
use App\Services\MappingExceptionService;
use App\Services\WeightConversionService;
use App\Utilities\CRMSourceUtility;

class ShopifyMappingService
{

    /**
     * @var CRMSourceValidation
     */
    private $crmSourceValidation;

    /**
     * @var CRMSource
     */
    private $shopifyCRMSource;

    /**
     * @var WeightConversionService
     */
    private $weightConversionService;

    /**
     * @var MappingExceptionService
     */
    private $mappingExceptionService;


    public function __construct()
    {
        $this->crmSourceValidation      = new CRMSourceValidation();
        $this->shopifyCRMSource         = $this->crmSourceValidation->idExists(CRMSourceUtility::SHOPIFY_ID);
        $this->weightConversionService  = new WeightConversionService();
        $this->mappingExceptionService  = new MappingExceptionService();
    }

    /**
     * @param   Client          $client
     * @param   ShopifyOrder    $shopifyOrder
     * @param   Order|null      $order
     * @return  Order
     */
    public function fromShopifyOrder (Client $client, ShopifyOrder $shopifyOrder, Order $order = null)
    {
        if (is_null($order))
            $order                          = new Order();

        $order->setExternalId($shopifyOrder->getId());
        $order->setExternalCreatedAt($this->fromShopifyDate($shopifyOrder->getCreatedAt()));

        $weightGrams                        = $shopifyOrder->getTotalWeight();
        $weightOunces                       = $this->weightConversionService->gramsToOunces($weightGrams);
        $order->setExternalWeight($weightOunces);

        $order->setCRMSource($this->shopifyCRMSource);
        $order->setClient($client);

        /**
         * Pricing...
         */
        $order->setBasePrice($shopifyOrder->getSubtotalPrice());
        $order->setTotalDiscount($shopifyOrder->getTotalDiscounts());
        $order->setTotalTaxes($shopifyOrder->getTotalTax());
        $order->setTotalItemsPrice($shopifyOrder->getTotalLineItemsPrice());
        $order->setTotalPrice($shopifyOrder->getTotalPrice());


        $providedAddress                    = $this->fromShopifyAddress($shopifyOrder->getShippingAddress());
        $order->setProvidedAddress($providedAddress);

        $shippingAddress                    = $this->fromShopifyAddress($shopifyOrder->getShippingAddress());
        if (!is_null($shopifyOrder->getEmail()))
            $shippingAddress->setEmail($shopifyOrder->getEmail());
        $order->setShippingAddress($shippingAddress);


        if (!is_null($shopifyOrder->getBillingAddress()))
        {
            $billingAddress             = $this->fromShopifyAddress($shopifyOrder->getBillingAddress());
            $order->setBillingAddress($billingAddress);
        }

        return $order;
    }

    /**
     * @param   ShopifyOrderLineItem $shopifyOrderLineItem
     * @return  OrderItem
     */
    public function fromShopifyOrderLineItem (ShopifyOrderLineItem $shopifyOrderLineItem)
    {
        $orderItem                  = new OrderItem();
        $orderItem->setExternalId($shopifyOrderLineItem->getId());
        $orderItem->setExternalProductId($shopifyOrderLineItem->getProductId());
        $orderItem->setExternalVariantId($shopifyOrderLineItem->getVariantId());
        $orderItem->setExternalVariantTitle($shopifyOrderLineItem->getVariantTitle());
        $orderItem->setSku($shopifyOrderLineItem->getSku());
        $orderItem->setQuantityPurchased($shopifyOrderLineItem->getQuantity());
        $orderItem->setQuantityToFulfill($shopifyOrderLineItem->getFulfillableQuantity());

        $orderItem->setBasePrice($shopifyOrderLineItem->getPrice());
        $orderItem->setTotalDiscount($shopifyOrderLineItem->getTotalDiscount());

        $totalTaxes                 = 0.00;
        foreach ($shopifyOrderLineItem->getTaxLines() AS $taxLine)
        {
            $totalTaxes             += $taxLine->getPrice();
        }
        $orderItem->setTotalTaxes($totalTaxes);

        return $orderItem;
    }

    /**
     * @param   ShopifyAddress $shopifyAddress
     * @return  Address
     */
    public function fromShopifyAddress (ShopifyAddress $shopifyAddress)
    {
        $address                        = new Address();
        $address->setFirstName(trim($shopifyAddress->getFirstName()));
        $address->setLastName(trim($shopifyAddress->getLastName()));
        $address->setCompany(trim($shopifyAddress->getCompany()));
        $address->setStreet1(trim($shopifyAddress->getAddress1()));
        $address->setStreet2(trim($shopifyAddress->getAddress2()));
        $address->setCity(trim($shopifyAddress->getCity()));
        $address->setPostalCode(trim($shopifyAddress->getZip()));

        if (is_null($shopifyAddress->getProvinceCode()))
            $address->setStateProvince(trim($shopifyAddress->getProvinceCode()));
        else
            $address->setStateProvince(trim($shopifyAddress->getProvince()));

        $address->setCountryCode(trim($shopifyAddress->getCountryCode()));
        $address->setPhone(trim($shopifyAddress->getPhone()));

        return $address;
    }

    /**
     * Creates a Product and ProductAlias from a ShopifyProduct
     * @param   Client $client
     * @param   ShopifyProduct $shopifyProduct
     * @param   Product $product
     * @return  Product
     */
    public function fromShopifyProduct (Client $client, ShopifyProduct $shopifyProduct, Product $product = null)
    {
        if (is_null($product))
            $product                        = new Product();

        $product->setName($shopifyProduct->getTitle());
        $product->setDescription($shopifyProduct->getBodyHtml());
        $product->setClient($client);

        return $product;
    }

    /**
     * @param   ShopifyProductImage $shopifyProductImage
     * @param   Image|null $image
     * @return  Image
     */
    public function fromShopifyProductImage (ShopifyProductImage $shopifyProductImage, Image $image = null)
    {
        if (is_null($image))
            $image                          = new Image();

        $image->setCrmSource($this->shopifyCRMSource);
        $image->setExternalId($shopifyProductImage->getId());
        $image->setExternalCreatedAt($this->fromShopifyDate($shopifyProductImage->getCreatedAt()));
        $image->setPath($shopifyProductImage->getSrc());
        return $image;
    }

    /**
     * @param   Client $client
     * @param   ShopifyProduct $shopifyProduct
     * @param   ProductAlias|null $productAlias
     * @return  ProductAlias
     */
    public function toLocalProductAlias (Client $client, ShopifyProduct $shopifyProduct, ProductAlias $productAlias = null)
    {
        if (is_null($productAlias))
            $productAlias                   = new ProductAlias();

        $productAlias->setClient($client);
        $productAlias->setCrmSource($this->shopifyCRMSource);
        $productAlias->setExternalId($shopifyProduct->getId());
        $productAlias->setExternalCreatedAt($this->fromShopifyDate($shopifyProduct->getCreatedAt()));

        return $productAlias;
    }

    /**
     * Creates or updates a Variant
     * @param   Product $product
     * @param   ShopifyVariant $shopifyVariant
     * @param   Variant $variant
     * @return  Variant
     */
    public function fromShopifyVariant (Product $product, ShopifyVariant $shopifyVariant, Variant $variant = null)
    {
        if (is_null($variant))
            $variant                        = new Variant();

        $variant->setProduct($product);
        $variant->setClient($product->getClient());
        $variant->setCrmSource($this->shopifyCRMSource);
        $variant->setTitle($shopifyVariant->getTitle());
        $variant->setPrice($shopifyVariant->getPrice());
        $variant->setBarcode($shopifyVariant->getBarcode());
        $variant->setExternalId($shopifyVariant->getId());
        $variant->setExternalCreatedAt($this->fromShopifyDate($shopifyVariant->getCreatedAt()));

        //  Convert grams to ounces
        $grams                              = $shopifyVariant->getGrams();
        if (is_null($grams) || empty($grams) || $grams < 0)
            $variant->setWeight(0.00);
        else
            $variant->setWeight($this->weightConversionService->gramsToOunces($grams));


        //  Lastly, handle the sku
        $variant->setOriginalSku($shopifyVariant->getSku());
        $sku                                = $this->mappingExceptionService->getShopifySku($product->getClient(), $shopifyVariant->getSku(), $shopifyVariant->getTitle());
        $variant->setSku($sku);

        return $variant;
    }

    /**
     * @param   string  $shopifyDate
     * @return  \DateTime
     */
    public function fromShopifyDate ($shopifyDate)
    {
        return \DateTime::createFromFormat('Y-m-d\TH:i:sO', $shopifyDate);
    }

}