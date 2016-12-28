<?php

namespace App\Console\Commands;


use App\Integrations\EasyPost\EasyPostConfiguration;
use App\Integrations\EasyPost\EasyPostIntegration;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostAddress;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Integrations\EasyPost\Models\Requests\GetEasyPostShipments;
use App\Jobs\Orders\OrderSkuMappingJob;
use App\Jobs\Shipments\CreateShipmentsJob;
use App\Models\Integrations\Validation\IntegratedServiceValidation;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\OMS\OrderStatusRepository;
use App\Services\CredentialService;
use App\Utilities\OrderStatusUtility;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use EntityManager;

class TestJunkCommand extends Command
{

    use DispatchesJobs;

    protected $signature    =   'turboship:test
                                {--PRODUCTS : Import products}
                                {--ORDERS : Import orders}';

    protected $description = 'Test whatever junk you want here';


    /**
     * @var ClientRepository
     */
    private $clientRepo;

    /**
     * @var IntegratedServiceValidation
     */
    private $integratedServiceValidation;

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepo;

    public function __construct()
    {
        parent::__construct();

        $this->integratedServiceValidation  = new IntegratedServiceValidation();
        $this->clientRepo                   = EntityManager::getRepository('App\Models\CMS\Client');
        $this->orderRepo                    = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderStatusRepo              = EntityManager::getRepository('App\Models\OMS\OrderStatus');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $order                              = $this->orderRepo->getOneById(1);
        $status                             = $this->orderStatusRepo->getOneById(OrderStatusUtility::PENDING_FULFILLMENT_ID);

        $order->addStatus($status);

        $this->orderRepo->saveAndCommit($order);
    }

}