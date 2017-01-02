<?php

namespace App\Services\Shipments;


use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Shipments\Postage;
use App\Models\Shipments\Rate;
use App\Models\Shipments\Shipment;
use App\Models\Support\Validation\ShipmentStatusValidation;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\EasyPost\EasyPostShipmentRepository;
use App\Services\EasyPost\Mapping\EasyPostShipmentMappingService;
use App\Utilities\IntegrationUtility;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use EntityManager;

class PostageService
{

    /**
     * @var IntegratedShippingApi
     */
    protected $integratedShippingApi;

    /**
     * @var OrderRepository
     */
    private $orderRepo;


    public function __construct(IntegratedShippingApi $integratedShippingApi)
    {
        $this->integratedShippingApi    = $integratedShippingApi;
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
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
            $shipment                   = $this->purchaseEasyPost($shipment, $rate);
        else
            throw new UnsupportedException('Integration is unsupported');

        $shipmentStatusValidation       = new ShipmentStatusValidation();
        $shipment->setStatus($shipmentStatusValidation->getFullyShipped());
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

        $shipmentStatusValidation       = new ShipmentStatusValidation();
        $shipment->setStatus($shipmentStatusValidation->getPending());

        return $shipment;
    }

    public function handleOrderShippedLogic (Shipment $shipment)
    {
        $shipmentStatusValidation   = new ShipmentStatusValidation();
        $orderCollection            = new ArrayCollection();

        foreach ($shipment->getItems() AS $shipmentItem)
        {
            $orderItem                  = $shipmentItem->getOrderItem();
            $order                      = $orderItem->getOrder();

            if (!$orderCollection->contains($order))
                $orderCollection->add($order);

            $quantityShipped            = $orderItem->getQuantityShipped() + $shipmentItem->getQuantity();
            $orderItem->setQuantityShipped($quantityShipped);

            if ($orderItem->getQuantityToFulfill() == $quantityShipped)
                $orderItem->setShipmentStatus($shipmentStatusValidation->getFullyShipped());
            else
                $orderItem->setShipmentStatus($shipmentStatusValidation->getPartiallyShipped());
        }

        $orders                         = $orderCollection->toArray();
        foreach ($orders AS $order)
        {
            $shipmentStatus         = $shipmentStatusValidation->getFullyShipped();
            foreach ($order->getItems() AS $orderItem)
            {
                if ($orderItem->getQuantityToFulfill() != $orderItem->getQuantityShipped())
                    $shipmentStatus         = $shipmentStatusValidation->getPartiallyShipped();
            }
            $order->setShipmentStatus($shipmentStatus);
            $this->orderRepo->saveAndCommit($order);
        }
    }


    public function handleOrderVoidedLogic (Shipment $shipment)
    {
        $shipmentStatusValidation   = new ShipmentStatusValidation();
        $orderCollection            = new ArrayCollection();

        foreach ($shipment->getItems() AS $shipmentItem)
        {
            $orderItem                  = $shipmentItem->getOrderItem();
            $order                      = $orderItem->getOrder();

            if (!$orderCollection->contains($order))
                $orderCollection->add($order);

            $quantityShipped            = $orderItem->getQuantityShipped() - $shipmentItem->getQuantity();
            $orderItem->setQuantityShipped($quantityShipped);

            if ($quantityShipped == 0)
                $orderItem->setShipmentStatus($shipmentStatusValidation->getPending());
            else
                $orderItem->setShipmentStatus($shipmentStatusValidation->getPartiallyShipped());
        }

        $orders                         = $orderCollection->toArray();
        foreach ($orders AS $order)
        {
            $shipmentStatus         = $shipmentStatusValidation->getPending();
            foreach ($order->getItems() AS $orderItem)
            {
                if ($orderItem->getQuantityShipped() > 0)
                    $shipmentStatus         = $shipmentStatusValidation->getPartiallyShipped();
            }
            $order->setShipmentStatus($shipmentStatus);
            $this->orderRepo->saveAndCommit($order);
        }
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
    private function voidEasyPost (Shipment $shipment)
    {
        $easyPostShipmentRepo           = new EasyPostShipmentRepository($this->integratedShippingApi);
        $easyPostShipment               = $easyPostShipmentRepo->void($shipment->getPostage()->getExternalShipmentId());

        return $shipment;
    }
}