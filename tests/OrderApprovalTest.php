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

        $this->assertInstanceOf('App\Models\Locations\Address', $order->getToAddress());


        $this->assertTrue(true);
    }
}
