<?php

namespace App\Services;


use App\Models\CMS\Client;
use App\Models\Locations\ProvidedAddress;
use App\Models\OMS\Order;
use App\Models\OMS\OrderItem;
use App\Models\OMS\OrderSource;
use App\Models\OMS\Validation\OrderSourceValidation;
use App\Services\Shopify\Models\Responses\ShopifyAddress;
use App\Services\Shopify\Models\Responses\ShopifyOrder;
use App\Services\Shopify\Models\Responses\ShopifyOrderLineItem;
use App\Utilities\OrderSourceUtility;

class ShopifyMappingService
{

    /**
     * @var OrderSourceValidation
     */
    private $orderSourceValidation;

    /**
     * @var OrderSource
     */
    private $shopifyOrderSource;


    public function __construct()
    {
        $this->orderSourceValidation    = new OrderSourceValidation();
        $this->shopifyOrderSource       = $this->orderSourceValidation->idExists(OrderSourceUtility::SHOPIFY_ID);
    }

    /**
     * @param   Client          $client
     * @param   ShopifyOrder    $shopifyOrder
     * @return  Order
     */
    public function fromShopifyOrder (Client $client, ShopifyOrder $shopifyOrder)
    {
        $order                          = new Order();
        $order->setExternalId($shopifyOrder->getId());
        $externalCreatedAt              = $this->fromShopifyDate($shopifyOrder->getCreatedAt());
        $order->setExternalCreatedAt($externalCreatedAt);
        $order->setSource($this->shopifyOrderSource);
        $order->setClient($client);

        /**
         * Pricing...
         */
        $order->setBasePrice($shopifyOrder->getSubtotalPrice());
        $order->setTotalDiscount($shopifyOrder->getTotalDiscounts());
        $order->setTotalTaxes($shopifyOrder->getTotalTax());
        $order->setTotalItemsPrice($shopifyOrder->getTotalLineItemsPrice());
        $order->setTotalPrice($shopifyOrder->getTotalPrice());

        $providedAddress                = $this->fromShopifyAddressToProvidedAddress($shopifyOrder->getShippingAddress());

        if (!is_null($shopifyOrder->getEmail()))
            $providedAddress->setEmail($shopifyOrder->getEmail());

        $order->setProvidedAddress($providedAddress);

        if (!is_null($shopifyOrder->getBillingAddress()))
        {
            $billingAddress             = $this->fromShopifyAddressToProvidedAddress($shopifyOrder->getBillingAddress());
            if (!is_null($shopifyOrder->getEmail()))
                $billingAddress->setEmail($shopifyOrder->getEmail());
            $order->setBillingAddress($billingAddress);
        }

        foreach ($shopifyOrder->getLineItems() AS $shopifyOrderLineItem)
        {
            $orderItem                  = $this->fromShopifyOrderLineItem($shopifyOrderLineItem);
            $order->addItem($orderItem);
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
     * @param   ShopifyAddress  $shopifyAddress
     * @return  ProvidedAddress
     */
    public function fromShopifyAddressToProvidedAddress (ShopifyAddress $shopifyAddress)
    {
        $providedAddress                = new ProvidedAddress();
        $providedAddress->setFirstName($shopifyAddress->getFirstName());
        $providedAddress->setLastName($shopifyAddress->getLastName());
        $providedAddress->setCompany($shopifyAddress->getCompany());
        $providedAddress->setStreet1($shopifyAddress->getAddress1());
        $providedAddress->setStreet2($shopifyAddress->getAddress2());
        $providedAddress->setCity($shopifyAddress->getCity());
        $providedAddress->setPostalCode($shopifyAddress->getZip());
        $providedAddress->setSubdivision($shopifyAddress->getProvinceCode());
        $providedAddress->setCountry($shopifyAddress->getCountryCode());
        $providedAddress->setPhone($shopifyAddress->getPhone());

        return $providedAddress;
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