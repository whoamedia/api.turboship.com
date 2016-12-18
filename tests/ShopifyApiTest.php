<?php

namespace Tests;

use App\Services\Shopify\Models\Requests\CreateShopifyProduct;
use App\Services\Shopify\Models\Requests\GetShopifyOrders;
use App\Services\Shopify\Models\Requests\GetShopifyProducts;
use App\Services\Shopify\Models\Responses\Product;

class ShopifyApiTest extends TestCase
{


    public function testIntegration ()
    {
        $shopifyService                 = $this->getShopifyService();

        $getShopifyProducts             = new GetShopifyProducts();
        $getShopifyProducts->setIds('9142425166');
        $products                       = $shopifyService->productApi->get($getShopifyProducts);
        foreach ($products AS $item)
        {
            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\Product', $item);
        }

        $product                        = $this->createProduct();
        $this->assertInstanceOf('App\Services\Shopify\Models\Responses\Product', $product);

        $product                        = $shopifyService->productApi->show($product->getId());
        $this->assertInstanceOf('App\Services\Shopify\Models\Responses\Product', $product);

        $variants                       = $shopifyService->productApi->getVariants($product->getId());
        foreach ($variants AS $item)
        {
            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\Variant', $item);
        }

        $product                        = $shopifyService->productApi->update($product);
        $this->assertInstanceOf('App\Services\Shopify\Models\Responses\Product', $product);

        $deletionResponse               = $shopifyService->productApi->delete($product->getId());
        $this->assertTrue($deletionResponse);
    }

    public function testOrderApi ()
    {
        $shopifyService                 = $this->getShopifyService();
        $getShopifyOrders               = new GetShopifyOrders();

        $orders                         = $shopifyService->orderApi->get($getShopifyOrders);

        $testOrder                      = sizeof($orders) > 0 ? $orders[0] : null;
        foreach ($orders AS $item)
        {
            $testOrder                  = $item;

            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyOrder', $item);

            if (!is_null($item->getBillingAddress()))
                $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyAddress', $item->getBillingAddress());

            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyAddress', $item->getShippingAddress());
            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyCustomer', $item->getCustomer());
            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyAddress', $item->getCustomer()->getDefaultAddress());

            foreach ($item->getLineItems() AS $lineItem)
            {
                $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyOrderLineItem', $lineItem);

                foreach ($lineItem->getTaxLines() AS $taxLine)
                {
                    $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyTaxLine', $taxLine);
                }
            }

            foreach ($item->getTaxLines() AS $taxLine)
            {
                $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyTaxLine', $taxLine);
            }
        }

        if (!is_null($testOrder))
        {
            $order                      = $shopifyService->orderApi->show($testOrder->getId());
            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyOrder', $order);
        }
    }

    public function testCarrierServiceApi ()
    {
        $shopifyService                 = $this->getShopifyService();
        $carrierServiceResponse         = $shopifyService->carrierServiceApi->get();
        foreach ($carrierServiceResponse AS $carrierService)
        {
            $this->assertInstanceOf('App\Services\Shopify\Models\Responses\ShopifyCarrierService', $carrierService);
        }
    }



    /**
     * @return Product
     */
    private function createProduct ()
    {
        $shopifyService                 = $this->getShopifyService();

        $product                        = new CreateShopifyProduct();
        $product->setTitle('Burton Custom Freestyle 151');
        $product->setBodyHtml("<strong>Good snowboard!</strong>");
        $product->setVendor('CheapUndies');
        $product->setProductType('Snowboard');

        $product                        = $shopifyService->productApi->create($product);
        return $product;
    }
}
