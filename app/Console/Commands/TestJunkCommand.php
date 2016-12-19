<?php

namespace App\Console\Commands;


use App\Jobs\Shopify\ShopifyOrderImportJob;
use App\Models\CMS\Validation\ClientValidation;
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
        $shopifyOrderImportJob          = new ShopifyOrderImportJob(1);
        $shopifyOrderImportJob->handle();
        //  $this->dispatch(new ShopifyOrderImportJob(1));
    }

}