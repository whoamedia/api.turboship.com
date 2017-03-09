<?php

namespace App\Console\Commands;


use App\Jobs\Shipments\AutomatedShippingJob;
use App\Jobs\Shopify\Orders\ShopifyCreateOrderJob;
use App\Models\Hardware\Validation\PrinterValidation;
use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use App\Repositories\Doctrine\Shipments\PostageRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\IPP\IPPService;
use App\Services\PrinterService;
use App\Services\Shipments\PostageService;
use App\Services\Shopify\ShopifyService;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use EntityManager;

use App\Services\IPP\Support\PrintIPP;
use Storage;

class TestJunkCommand extends Command
{

    use DispatchesJobs;

    protected $signature    =   'turboship:test
                                {--PRODUCTS : Import products}
                                {--ORDERS : Import orders}';

    protected $description = 'Test whatever junk you want here';

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * @var IntegratedShippingApiRepository
     */
    private $integratedShippingApiRepo;

    /**
     * @var PostageRepository
     */
    private $postageRepo;

    /**
     * @var IntegratedShoppingCartRepository
     */
    private $integratedShoppingCartRepo;

    public function __construct()
    {
        parent::__construct();

        $this->shipmentRepo                 = EntityManager::getRepository('App\Models\Shipments\Shipment');
        $this->postageRepo                  = EntityManager::getRepository('App\Models\Shipments\Postage');
        $this->integratedShoppingCartRepo   = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $shoppingCartIntegration            = $this->integratedShoppingCartRepo->getOneById(1);
        $shopifyService                     = new ShopifyService($shoppingCartIntegration);
        $total                              = $shopifyService->getOrdersShippedCount();

        $this->info('Found ' . $total . ' results');
        $totalPages                         = (int)ceil($total / 250);


        $shopifyService->shopifyClient->getConfig()->setJsonOnly(true);
        for ($page = 1; $page <= $totalPages; $page++)
        {
            $this->info('On page ' . $page . ' of ' . $totalPages);
            set_time_limit(60);
            $shopifyOrdersResponse          = $shopifyService->getOrdersShipped($page, 250);
            $orderArray                     = json_decode($shopifyOrdersResponse, true);
            foreach ($orderArray AS $shopifyOrder)
            {
                try
                {
                    $job                        = (new ShopifyCreateOrderJob(json_encode($shopifyOrder), $shoppingCartIntegration->getId()))->onQueue('shopifyOrders');
                    $this->dispatch($job);
                }
                catch (\Pheanstalk\Exception $exception)
                {
                    continue;
                }

            }
            usleep(250000);
        }
    }

}