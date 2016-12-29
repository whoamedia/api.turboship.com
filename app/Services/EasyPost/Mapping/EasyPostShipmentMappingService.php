<?php

namespace App\Services\EasyPost\Mapping;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostCustomsItem;
use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Models\Shipments\Shipment;
use App\Models\Shipments\ShipmentItem;

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

    public function __construct()
    {
        $this->easyPostAddressMappingService    = new EasyPostAddressMappingService();
        $this->easyPostParcelMappingService     = new EasyPostParcelMappingService();

    }

    /**
     * @param   Shipment $shipment
     * @return  CreateEasyPostShipment
     */
    public function handleMapping (Shipment $shipment)
    {
        $createEasyPostShipment         = $this->toEasyPostShipment($shipment);

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
}