<?php

namespace App\Services\Shopify\Mapping;


use App\Integrations\Shopify\Models\Responses\ShopifyAddress;
use App\Integrations\Shopify\Models\Responses\ShopifyOrder;
use App\Integrations\Shopify\Models\Responses\ShopifyOrderLineItem;
use App\Models\CMS\Client;
use App\Models\Locations\Address;
use App\Models\OMS\Order;
use App\Models\OMS\OrderItem;
use App\Repositories\Doctrine\OMS\OrderItemRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Utilities\CRMSourceUtility;
use Bugsnag;
use EntityManager;

class ShopifyOrderMappingService extends BaseShopifyMappingService
{

    /**
     * @var OrderRepository
     */
    protected $orderRepo;

    /**
     * @var OrderItemRepository
     */
    protected $orderItemRepo;


    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderItemRepo                = EntityManager::getRepository('App\Models\OMS\OrderItem');
    }

    /**
     * @param   ShopifyOrder $shopifyOrder
     * @return  Order
     */
    public function handleMapping (ShopifyOrder $shopifyOrder)
    {
        $order                              = $this->findLocalOrder($shopifyOrder);
        $order                              = $this->toOrder($shopifyOrder, $order);

        foreach ($shopifyOrder->getLineItems() AS $shopifyOrderLineItem)
        {
            if ($this->shouldImportOrderItem($shopifyOrderLineItem) == false)
                continue;

            $orderItem                      = $this->findLocalOrderItem($shopifyOrderLineItem, $order->getId());
            $orderItem                      = $this->toOrderItem($shopifyOrderLineItem, $orderItem);

            if (is_null($orderItem->getSku()))
                Bugsnag::leaveBreadcrumb('shopifyOrder', \Bugsnag\Breadcrumbs\Breadcrumb::MANUAL_TYPE, $shopifyOrder->jsonSerialize());

            if (is_null($orderItem->getId()))
                $order->addItem($orderItem);
        }

        return $order;
    }

    /**
     * @param   ShopifyOrder    $shopifyOrder
     * @param   Order|null      $order
     * @return  Order
     */
    public function toOrder (ShopifyOrder $shopifyOrder, Order $order = null)
    {
        if (is_null($order))
            $order                          = new Order();

        $order->setCRMSource($this->shopifyCRMSource);
        $order->setClient($this->client);

        $order->setExternalId($shopifyOrder->getId());
        $order->setExternalCreatedAt($this->toDate($shopifyOrder->getCreatedAt()));

        $weightGrams                        = $shopifyOrder->getTotalWeight();
        $weightOunces                       = $this->weightConversionService->gramsToOunces($weightGrams);
        $order->setExternalWeight($weightOunces);

        /**
         * Pricing...
         */
        $order->setBasePrice($shopifyOrder->getSubtotalPrice());
        $order->setTotalDiscount($shopifyOrder->getTotalDiscounts());
        $order->setTotalTaxes($shopifyOrder->getTotalTax());
        $order->setTotalItemsPrice($shopifyOrder->getTotalLineItemsPrice());
        $order->setTotalPrice($shopifyOrder->getTotalPrice());

        $providedAddress                    = $this->toAddress($shopifyOrder->getShippingAddress(), $order->getProvidedAddress());
        $order->setProvidedAddress($providedAddress);

        $shippingAddress                    = $this->toAddress($shopifyOrder->getShippingAddress(), $order->getShippingAddress());
        if (!is_null($shopifyOrder->getEmail()))
            $shippingAddress->setEmail($shopifyOrder->getEmail());
        $order->setShippingAddress($shippingAddress);


        if (!is_null($shopifyOrder->getBillingAddress()))
        {
            $billingAddress             = $this->toAddress($shopifyOrder->getBillingAddress(), $order->getBillingAddress());
            $order->setBillingAddress($billingAddress);
        }

        return $order;
    }

    /**
     * @param   ShopifyOrderLineItem $shopifyOrderLineItem
     * @param   OrderItem   $orderItem
     * @return  OrderItem
     */
    public function toOrderItem (ShopifyOrderLineItem $shopifyOrderLineItem, OrderItem $orderItem = null)
    {
        if (is_null($orderItem))
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
     * @param   Address|null $address
     * @return  Address
     */
    public function toAddress (ShopifyAddress $shopifyAddress, Address $address = null)
    {
        if (is_null($address))
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
     * @param   ShopifyOrder $shopifyOrder
     * @return  Order|null
     */
    public function findLocalOrder (ShopifyOrder $shopifyOrder)
    {
        $orderQuery     = [
            'clientIds'             => $this->client->getId(),
            'crmSourceIds'          => CRMSourceUtility::SHOPIFY_ID,
            'externalIds'           => $shopifyOrder->getId(),
        ];

        $orderResult                = $this->orderRepo->where($orderQuery);
        return sizeof($orderResult) == 1 ? $orderResult[0] : null;
    }

    /**
     * @param   ShopifyOrderLineItem $shopifyOrderLineItem
     * @param   int|null    $orderId
     * @return  OrderItem|null
     */
    public function findLocalOrderItem (ShopifyOrderLineItem $shopifyOrderLineItem, $orderId = null)
    {
        $orderItemQuery = [
            'clientIds'             => $this->client->getId(),
            'crmSourceIds'          => CRMSourceUtility::SHOPIFY_ID,
            'externalIds'           => $shopifyOrderLineItem->getId()
        ];

        if (!is_null($orderId))
            $orderItemQuery['orderIds'] = $orderId;

        $orderItemResult            = $this->orderItemRepo->where($orderItemQuery);
        return sizeof($orderItemResult) == 1 ? $orderItemResult[0] : null;
    }

    /**
     * @param   ShopifyOrder $shopifyOrder
     * @return  bool
     */
    public function shouldImportOrder (ShopifyOrder $shopifyOrder)
    {
        if ($shopifyOrder->isTest())
            return false;
        else if ($shopifyOrder->getFinancialStatus() != 'paid')
            return false;
        else if ($shopifyOrder->getFulfillmentStatus() == 'shipped')
            return false;
        else
            return true;
    }

    /**
     * @param   ShopifyOrderLineItem $shopifyOrderLineItem
     * @return  bool
     */
    public function shouldImportOrderItem (ShopifyOrderLineItem $shopifyOrderLineItem)
    {
        if ($shopifyOrderLineItem->getRequiresShipping() == false)
            return false;
        else
            return true;
    }

}