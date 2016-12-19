<?php

namespace App\Console\Commands;


use App\Jobs\Shopify\ShopifyOrderImportJob;
use App\Jobs\Shopify\ShopifyProductImportJob;
use App\Models\CMS\Validation\ClientValidation;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Utilities\CRMSourceUtility;
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
     * @var CRMSourceUtility
     */
    private $crmSourceUtility;

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
        $this->crmSourceUtility         = new CRMSourceUtility();
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
        $shopifyProductImportJob        = new ShopifyProductImportJob(1);
        $shopifyProductImportJob->handle();
        RETURN;



        $this->call('turboship:reboot');

        $this->info('Importing Shopify products...');
        $shopifyProductImportJob        = new ShopifyProductImportJob(1);
        $shopifyProductImportJob->handle();

        $this->info('Importing Shopify orders...');
        $shopifyOrderImportJob          = new ShopifyOrderImportJob(1);
        $shopifyOrderImportJob->handle();
        //  $this->dispatch(new ShopifyOrderImportJob(1));
    }

}