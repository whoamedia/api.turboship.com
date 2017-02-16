<?php

namespace App\Jobs\Shipments;


use App\Jobs\Job;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Repositories\Doctrine\Shipments\ShippingContainerRepository;
use App\Services\Shipments\PostageService;
use App\Utilities\CarrierUtility;
use App\Utilities\ShipmentStatusUtility;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;
use jamesvweston\EasyPost\Exceptions\EasyPostCustomsInfoException;

class AutomatedShippingJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, DispatchesJobs;


    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * @var ShippingContainerRepository
     */
    private $shippingContainerRepo;

    /**
     * @var int
     */
    private $shipmentId;



    public function __construct($shipmentId)
    {
        parent::__construct();

        $this->shipmentId               = $shipmentId;
    }

    public function handle()
    {
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
        $this->shippingContainerRepo    = EntityManager::getRepository('App\Models\Shipments\ShippingContainer');

        $shipment                       = $this->shipmentRepo->getOneById($this->shipmentId);

        if ($shipment->getStatus()->getId() != ShipmentStatusUtility::PENDING)
            return;
        else if (!is_null($shipment->getPostage()))
            return;

        $integratedShippingApi          = $shipment->getClient()->getOptions()->getDefaultIntegratedShippingApi();
        $postageService                 = new PostageService($integratedShippingApi);


        if (is_null($shipment->getShippingContainer()))
        {
            $shippingContainerResults   = $this->shippingContainerRepo->where([]);
            $index                      = rand(0, sizeof($shippingContainerResults) - 1);
            $shipment->setShippingContainer($shippingContainerResults[$index]);
        }


        if (is_null($shipment->getWeight()))
        {
            $shipment->setWeight(rand(4, 100) . '.' . rand(10, 99));
        }



        if (empty($shipment->getRates()))
        {
            $postageService->rate($shipment);
        }


        $candidateRates                 = [];
        foreach ($shipment->getRates() AS $rate)
        {
            if ($rate->getShippingApiService()->getService()->getCarrier()->getId() == CarrierUtility::UPS_MAIL_INNOVATIONS)
                continue;

            $candidateRates[]           = $rate;
        }

        $index                          = rand(0, sizeof($candidateRates) - 1);
        $rate                           = $candidateRates[$index];


        $postageService->purchase($shipment, $rate);
        $this->shipmentRepo->saveAndCommit($shipment);
        $postageService->handleOrderShippedLogic($shipment);
    }

}