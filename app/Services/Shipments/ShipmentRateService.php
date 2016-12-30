<?php

namespace App\Services\Shipments;


use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Shipments\Shipment;
use App\Repositories\EasyPost\EasyPostShipmentRepository;
use App\Services\EasyPost\Mapping\EasyPostShipmentMappingService;
use App\Utilities\IntegrationUtility;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class ShipmentRateService
{

    /**
     * @var IntegratedShippingApi
     */
    protected $integratedShippingApi;


    public function __construct(IntegratedShippingApi $integratedShippingApi)
    {
        $this->integratedShippingApi    = $integratedShippingApi;
    }


    public function rate (Shipment $shipment)
    {
        if ($this->integratedShippingApi->getIntegration()->getId() == IntegrationUtility::EASYPOST_ID)
            return $this->rateEasyPost($shipment);
        else
            throw new UnsupportedException('Integration is unsupported');
    }


    public function rateEasyPost (Shipment $shipment)
    {
        $easyPostShipmentRepo           = new EasyPostShipmentRepository($this->integratedShippingApi);

        $easyPostShipmentMappingService = new EasyPostShipmentMappingService();
        $createEasyPostShipment         = $easyPostShipmentMappingService->handleMapping($shipment);

        $easyPostShipmentRepo->rate($createEasyPostShipment);
    }
}