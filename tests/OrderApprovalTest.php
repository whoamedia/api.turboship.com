<?php

namespace Tests;


use App\Services\Order\OrderApprovalService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Factories\OrderFactory;

class OrderApprovalTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOrderApprovalService()
    {
        $orderFactory                   = new OrderFactory();
        $approvalService                = new OrderApprovalService();

        $order                          = $orderFactory->getValidOrder();
        $approvalService->processOrder($order);

        $this->assertInstanceOf('App\Models\Locations\Address', $order->getShippingAddress());
        $this->assertInstanceOf('App\Models\Locations\Subdivision', $order->getShippingAddress()->getSubdivision());
        $this->assertInstanceOf('App\Models\Locations\Country', $order->getShippingAddress()->getSubdivision()->getCountry());
        $this->assertInstanceOf('App\Models\OMS\Order', $order);

        foreach ($order->getItems() AS $item)
        {
            $this->assertInstanceOf('App\Models\OMS\OrderItem', $item);
        }


        $this->assertTrue(true);
    }
}
