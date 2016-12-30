<?php

namespace App\Services\EasyPost\Mapping;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostCustomsInfo;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostCustomsItem;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Integrations\EasyPost\Models\Responses\EasyPostRate;
use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Shipments\Rate;
use App\Models\Shipments\Shipment;
use App\Models\Shipments\ShipmentItem;
use App\Repositories\Doctrine\Integrations\ShippingApiServiceRepository;
use App\Utilities\IntegrationUtility;
use EntityManager;

class EasyPostShipmentMappingService extends BaseEasyPostMappingService
{

    /**
     * @var EasyPostAddressMappingService
     */
    protected $easyPostAddressMappingService;

    /**
     * @var EasyPostParcelMappingService
     */
    protected $easyPostParcelMappingService;

    /**
     * @var ShippingApiServiceRepository
     */
    protected $shippingApiServiceRepo;

    public function __construct()
    {
        $this->easyPostAddressMappingService    = new EasyPostAddressMappingService();
        $this->easyPostParcelMappingService     = new EasyPostParcelMappingService();
        $this->shippingApiServiceRepo           = EntityManager::getRepository('App\Models\Integrations\ShippingApiService');

    }

    /**
     * @param   Shipment $shipment
     * @return  CreateEasyPostShipment
     */
    public function handleMapping (Shipment $shipment)
    {
        $createEasyPostShipment         = $this->toEasyPostShipment($shipment);
        if ($createEasyPostShipment->getToAddress()->getCountry() != 'US')
        {
            $createEasyPostCustomsInfo  = $this->toEasyPostCustomsInfo($shipment);
            $createEasyPostShipment->setCustomsInfo($createEasyPostCustomsInfo);
        }

        return $createEasyPostShipment;
    }

    /**
     * @param   Shipment $shipment
     * @return  CreateEasyPostShipment
     */
    public function toEasyPostShipment (Shipment $shipment)
    {
        $createEasyPostShipment          = new CreateEasyPostShipment();
        $createEasyPostShipment->setReference($shipment->getId());

        $toAddress                      = $this->easyPostAddressMappingService->toEasyPostAddress($shipment->getToAddress());
        $createEasyPostShipment->setToAddress($toAddress);

        $fromAddress                    = $this->easyPostAddressMappingService->toEasyPostAddress($shipment->getFromAddress());
        $createEasyPostShipment->setFromAddress($fromAddress);

        $returnAddress                  = $this->easyPostAddressMappingService->toEasyPostAddress($shipment->getReturnAddress());
        $createEasyPostShipment->setFromAddress($returnAddress);

        $easyPostParcel                 = $this->easyPostParcelMappingService->toEasyPostParcel($shipment->getShippingContainer(), $shipment->getWeight());
        $createEasyPostShipment->setParcel($easyPostParcel);

        return $createEasyPostShipment;
    }

    /**
     * @param   Shipment $shipment
     * @return  CreateEasyPostCustomsInfo
     */
    public function toEasyPostCustomsInfo (Shipment $shipment)
    {
        $createEasyPostCustomsInfo      = new CreateEasyPostCustomsInfo();

        $customs_items                  = [];
        foreach ($shipment->getItems() AS $shipmentItem)
        {
            $customs_items[]            = $this->toEasyPostCustomsItem($shipmentItem);
        }
        $createEasyPostCustomsInfo->setCustomsItems($customs_items);

        return $createEasyPostCustomsInfo;
    }
    /**
     * @param   ShipmentItem $shipmentItem
     * @return  CreateEasyPostCustomsItem
     */
    public function toEasyPostCustomsItem (ShipmentItem $shipmentItem)
    {
        $createEasyPostCustomsItem      = new CreateEasyPostCustomsItem();
        $createEasyPostCustomsItem->setDescription($shipmentItem->getOrderItem()->getVariant()->getProduct()->getName());
        $createEasyPostCustomsItem->setQuantity($shipmentItem->getQuantity());
        $createEasyPostCustomsItem->setWeight($shipmentItem->getOrderItem()->getVariant()->getWeight());
        $createEasyPostCustomsItem->setValue($shipmentItem->getOrderItem()->getBasePrice());

        return $createEasyPostCustomsItem;
    }

    /**
     * @param   EasyPostRate $easyPostRate
     * @param   IntegratedShippingApi $integratedShippingApi
     * @return  Rate
     */
    public function toLocalRate (EasyPostRate $easyPostRate, IntegratedShippingApi $integratedShippingApi)
    {
        $rate                           = new Rate();
        $rate->setExternalId($easyPostRate->getId());
        $rate->setExternalShipmentId($easyPostRate->getShipmentId());
        $rate->setIntegratedShippingApi($integratedShippingApi);
        $rate->setRate($easyPostRate->getRate());

        $shippingApiService             = $this->getShippingApiService($easyPostRate->getCarrier(), $easyPostRate->getService());
        $rate->setShippingApiService($shippingApiService);

        return $rate;
    }


    public function getShippingApiService ($shippingApiCarrierName, $shippingApiServiceName)
    {
        $query      = [
            'names'                         => $shippingApiServiceName,
            'shippingApiCarrierNames'       => $shippingApiCarrierName,
            'shippingApiIntegrationIds'     => IntegrationUtility::EASYPOST_ID,
        ];

        return $this->shippingApiServiceRepo->getOneByQuery($query);
    }
}