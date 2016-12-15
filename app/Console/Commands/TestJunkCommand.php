<?php

namespace App\Console\Commands;


use App\Models\CMS\Validation\ClientValidation;
use App\Models\OMS\Order;
use App\Models\OMS\OrderItem;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Utilities\OrderSourceUtility;
use App\Utilities\OrderStatusUtility;
use Illuminate\Console\Command;
use EntityManager;

class TestJunkCommand extends Command
{

    protected $signature = 'turboship:test';

    protected $description = 'Test whatever junk you want here';


    /**
     * @var ClientValidation
     */
    private $clientValidation;

    /**
     * @var OrderSourceUtility
     */
    private $orderSourceUtility;

    /**
     * @var OrderStatusUtility
     */
    private $orderStatusUtility;

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    public function __construct()
    {
        parent::__construct();

        $this->clientValidation         = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));
        $this->orderSourceUtility       = new OrderSourceUtility();
        $this->orderStatusUtility       = new OrderStatusUtility();
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client                         = $this->clientValidation->idExists(1);
        $shopify                        = $this->orderSourceUtility->getShopify();


        $order                          = new Order();
        $order->setExternalId('asdfasdfdaddffsd');
        $order->setSource($shopify);
        $order->setClient($client);
        //  $order->addStatus($backOrderedStatus);

        $orderItem                      = new OrderItem();
        $orderItem->setExternalId('asdf');
        $orderItem->setSku('asdfas');
        $orderItem->setQuantity(1);
        $orderItem->setDeclaredValue(302.42);

        $order->addItem($orderItem);

        $this->orderRepo->saveAndCommit($order);

        dd($order->jsonSerialize());
    }

}