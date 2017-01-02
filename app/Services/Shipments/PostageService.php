<?php

namespace App\Services\Shipments;


use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Shipments\Postage;
use App\Models\Shipments\Rate;
use App\Models\Shipments\Shipment;
use App\Repositories\EasyPost\EasyPostShipmentRepository;
use App\Services\EasyPost\Mapping\EasyPostShipmentMappingService;
use App\Utilities\IntegrationUtility;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\UnsupportedException;

class PostageService
{

    /**
     * @var IntegratedShippingApi
     */
    protected $integratedShippingApi;


    public function __construct(IntegratedShippingApi $integratedShippingApi)
    {
        $this->integratedShippingApi    = $integratedShippingApi;
    }

    /**
     * @param   Shipment    $shipment
     * @param   bool        $clearRates
     * @return  Shipment
     */
    public function rate (Shipment $shipment, $clearRates = true)
    {
        $shipment->canRate();

        if ($clearRates == true)
            $shipment->clearRates();

        if ($this->integratedShippingApi->getIntegration()->getId() == IntegrationUtility::EASYPOST_ID)
            return $this->rateEasyPost($shipment);
        else
            throw new UnsupportedException('Integration is unsupported');
    }

    /**
     * @param   Shipment $shipment
     * @return  Shipment
     */
    private function rateEasyPost (Shipment $shipment)
    {
        $easyPostShipmentRepo           = new EasyPostShipmentRepository($this->integratedShippingApi);
        $easyPostShipmentMappingService = new EasyPostShipmentMappingService();
        $createEasyPostShipment         = $easyPostShipmentMappingService->handleMapping($shipment);
        $easyPostShipment               = $easyPostShipmentRepo->rate($createEasyPostShipment);

        foreach ($easyPostShipment->getRates() AS $easyPostRate)
        {
            $rate                       = $easyPostShipmentMappingService->toLocalRate($easyPostRate, $this->integratedShippingApi);
            $shipment->addRate($rate);
        }

        return $shipment;
    }

    /**
     * @param   Shipment $shipment
     * @param   Rate $rate
     * @return  Shipment
     */
    public function purchase (Shipment $shipment, Rate $rate)
    {
        $shipment->canPurchasePostage($rate);

        if ($this->integratedShippingApi->getIntegration()->getId() == IntegrationUtility::EASYPOST_ID)
            return $this->purchaseEasyPost($shipment, $rate);
        else
            throw new UnsupportedException('Integration is unsupported');
    }

    /**
     * @param   Shipment    $shipment
     * @param   Rate        $rate
     * @return  Shipment
     */
    private function purchaseEasyPost (Shipment $shipment, Rate $rate)
    {
        $easyPostShipmentRepo           = new EasyPostShipmentRepository($this->integratedShippingApi);
        $easyPostShipment               = $easyPostShipmentRepo->buy($rate->getExternalShipmentId(), $rate->getExternalId());
        $postage                        = new Postage();
        $postage->setShipment($shipment);
        $postage->setIntegratedShippingApi($this->integratedShippingApi);
        $postage->setShippingApiService($rate->getShippingApiService());
        $postage->setLabelPath($easyPostShipment->getPostageLabel()->getLabelUrl());
        $postage->setBasePrice($rate->getRate());
        $postage->setTotalPrice($rate->getRate());
        $postage->setWeight($shipment->getWeight());
        $postage->setTrackingNumber($easyPostShipment->getTrackingCode());
        $postage->setExternalId($easyPostShipment->getPostageLabel()->getId());
        $postage->setExternalShipmentId($rate->getExternalShipmentId());
        $postage->setExternalRateId($rate->getExternalId());
        $shipment->setPostage($postage);

        return $shipment;
    }

    /**
     * @param   Shipment $shipment
     * @return  Shipment
     */
    public function void (Shipment $shipment)
    {
        $shipment->canVoidPostage();

        if ($this->integratedShippingApi->getIntegration()->getId() == IntegrationUtility::EASYPOST_ID)
            $this->voidEasyPost($shipment);
        else
            throw new UnsupportedException('Integration is unsupported');


        $shipment->getPostage()->setVoidedAt(new \DateTime());
        $shipment->setPostage(null);
        $shipment->clearRates();
        return $shipment;
    }

    /**
     * @param   Shipment $shipment
     * @return  Shipment
     */
    private function voidEasyPost (Shipment $shipment)
    {
        $easyPostShipmentRepo           = new EasyPostShipmentRepository($this->integratedShippingApi);
        $easyPostShipment               = $easyPostShipmentRepo->void($shipment->getPostage()->getExternalShipmentId());

        return $shipment;
    }
}