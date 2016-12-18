<?php

namespace Tests;

use App\Integrations\Shopify\Models\Requests\CreateShopifyProduct;
use App\Integrations\Shopify\Models\Requests\GetShopifyOrders;
use App\Integrations\Shopify\Models\Requests\GetShopifyProducts;

class ShopifyApiTest extends TestCase
{


    public function testIntegration ()
    {
        $shopifyIntegration             = $this->getShopifyIntegration();

        $getShopifyProducts             = new GetShopifyProducts();
        $getShopifyProducts->setIds('9142425166');
        $products                       = $shopifyIntegration->productApi->get($getShopifyProducts);
        foreach ($products AS $item)
        {
            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyProduct', $item);
        }

        $product                        = $this->createProduct();
        $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyProduct', $product);

        $product                        = $shopifyIntegration->productApi->show($product->getId());
        $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyProduct', $product);

        $variants                       = $shopifyIntegration->productApi->getVariants($product->getId());
        foreach ($variants AS $item)
        {
            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyVariant', $item);
        }

        $product                        = $shopifyIntegration->productApi->update($product);
        $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyProduct', $product);

        $deletionResponse               = $shopifyIntegration->productApi->delete($product->getId());
        $this->assertTrue($deletionResponse);
    }

    public function testOrderApi ()
    {
        $shopifyIntegration                 = $this->getShopifyIntegration();
        $getShopifyOrders               = new GetShopifyOrders();

        $orders                         = $shopifyIntegration->orderApi->get($getShopifyOrders);

        $testOrder                      = sizeof($orders) > 0 ? $orders[0] : null;
        foreach ($orders AS $item)
        {
            $testOrder                  = $item;

            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyOrder', $item);

            if (!is_null($item->getBillingAddress()))
                $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyAddress', $item->getBillingAddress());

            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyAddress', $item->getShippingAddress());
            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyCustomer', $item->getCustomer());
            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyAddress', $item->getCustomer()->getDefaultAddress());

            foreach ($item->getLineItems() AS $lineItem)
            {
                $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyOrderLineItem', $lineItem);

                foreach ($lineItem->getTaxLines() AS $taxLine)
                {
                    $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyTaxLine', $taxLine);
                }
            }

            foreach ($item->getTaxLines() AS $taxLine)
            {
                $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyTaxLine', $taxLine);
            }
        }

        if (!is_null($testOrder))
        {
            $order                      = $shopifyIntegration->orderApi->show($testOrder->getId());
            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyOrder', $order);
        }
    }

    public function testCarrierServiceApi ()
    {
        $shopifyIntegration                 = $this->getShopifyIntegration();
        $carrierServiceResponse         = $shopifyIntegration->carrierServiceApi->get();
        foreach ($carrierServiceResponse AS $carrierService)
        {
            $this->assertInstanceOf('App\Integrations\Shopify\Models\Responses\ShopifyCarrierService', $carrierService);
        }
    }



    /**
     * @return Product
     */
    private function createProduct ()
    {
        $shopifyIntegration                 = $this->getShopifyIntegration();

        $product                        = new CreateShopifyProduct();
        $product->setTitle('Burton Custom Freestyle 151');
        $product->setBodyHtml("<strong>Good snowboard!</strong>");
        $product->setVendor('CheapUndies');
        $product->setProductType('Snowboard');

        $product                        = $shopifyIntegration->productApi->create($product);
        return $product;
    }
}
