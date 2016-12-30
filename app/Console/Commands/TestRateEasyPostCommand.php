<?php

namespace App\Console\Commands;


use App\Models\Shipments\Validation\ShippingContainerValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Services\Shipments\ShipmentRateService;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use EntityManager;

class TestRateEasyPostCommand extends Command
{

    use DispatchesJobs;


    protected $signature = 'test:easypost:rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk rate easy post shipments';


    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * @var IntegratedShippingApiRepository
     */
    private $integratedShippingApiRepo;

    public function __construct()
    {
        parent::__construct();

        $this->shipmentRepo                 = EntityManager::getRepository('App\Models\Shipments\Shipment');
        $this->integratedShippingApiRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedShippingApi');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $integratedShippingApi              = $this->integratedShippingApiRepo->getOneById(2);
        $shipmentRateService                = new ShipmentRateService($integratedShippingApi);
        $shippingContainerValidation        = new ShippingContainerValidation();

        $shipmentsResponse                  = $this->shipmentRepo->where([], true);
        foreach ($shipmentsResponse AS $shipment)
        {
            $this->info('On shipment id ' . $shipment->getId());
            $shipment->setWeight(rand(20, 100) . '.' . rand(1, 99));
            $shippingContainer              = $shippingContainerValidation->idExists(rand(1, 13));
            $shipment->setShippingContainer($shippingContainer);

            $shipmentRateService->rate($shipment);
            $this->shipmentRepo->saveAndCommit($shipment);

            $rateId                         = rand(0, sizeof($shipment->getRates()) - 1);
            $rate                           = $shipment->getRates()[$rateId];
            $shipmentRateService->purchase($shipment, $rate);

            $this->shipmentRepo->saveAndCommit($shipment);
        }
    }

}
