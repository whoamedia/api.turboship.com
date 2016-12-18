<?php

namespace Tests;

use App\Services\Shopify\Models\Requests\CreateShopifyProduct;
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
