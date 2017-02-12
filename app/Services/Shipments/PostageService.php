<?php

namespace App\Services\Shipments;


use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Shipments\Postage;
use App\Models\Shipments\Rate;
use App\Models\Shipments\Shipment;
use App\Models\Support\Validation\ShipmentStatusValidation;
use App\Repositories\Doctrine\OMS\OrderItemRepository;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\EasyPost\EasyPostService;
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

    /**
     * @var OrderItemRepository
     */
    private $orderItemRepo;


    public function __construct(IntegratedShippingApi $integratedShippingApi)
    {
        $this->integratedShippingApi    = $integratedShippingApi;
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->orderItemRepo            = EntityManager::getRepository('App\Models\OMS\OrderItem');
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
            $this->rateEasyPost($shipment);
        else
            throw new UnsupportedException('Integration is unsupported');

        foreach ($shipment->getRates() AS $rate)
        {
            $rate->setWeight($shipment->getWeight());
        }

        return $shipment;
    }

    /**
     * @param   Shipment $shipment
     * @return  Shipment
     */
    private function rateEasyPost (Shipment $shipment)
    {
        $easyPostService                = new EasyPostService($this->integratedShippingApi);
        $easyPostShipmentMappingService = new EasyPostShipmentMappingService();
        $createEasyPostShipment         = $easyPostShipmentMappingService->handleMapping($shipment);
        $easyPostShipment               = $easyPostService->rateShipment($createEasyPostShipment);

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

        $rate->setPurchased(true);
        $shipment->getPostage()->setRate($rate);
        $shipmentStatusValidation       = new ShipmentStatusValidation();
        $shipment->setStatus($shipmentStatusValidation->getFullyShipped());
        $shipment->setShippedAt(new \DateTime());
        $shipment->setService($rate->getShippingApiService()->getService());
        return $shipment;
    }

    /**
     * @param   Shipment $shipment
     * @return  Shipment
     */
    public function void (Shipment $shipment)
    {
        if ($this->integratedShippingApi->getIntegration()->getId() == IntegrationUtility::EASYPOST_ID)
            $this->voidEasyPost($shipment);
        else
            throw new UnsupportedException('Integration is unsupported');


        $shipment->getPostage()->setVoidedAt(new \DateTime());
        $shipment->setShippedAt(null);
        $shipment->setPostage(null);
        $shipment->clearRates();

        $shipmentStatusValidation       = new ShipmentStatusValidation();
        $shipment->setStatus($shipmentStatusValidation->getPending());
        $shipment->setService(null);
        return $shipment;
    }

    public function handleOrderShippedLogic (Shipment $shipment)
    {
        $shipmentStatusValidation       = new ShipmentStatusValidation();
        $orderCollection                = new ArrayCollection();

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

            $this->orderItemRepo->saveAndCommit($orderItem);
        }

        $orders                         = $orderCollection->toArray();
        foreach ($orders AS $order)
        {
            $shipmentStatus             = $shipmentStatusValidation->getFullyShipped();
            foreach ($order->getItems() AS $orderItem)
            {
                if ($orderItem->getQuantityToFulfill() != $orderItem->getQuantityShipped())
                    $shipmentStatus     = $shipmentStatusValidation->getPartiallyShipped();

                $orderItem->setShipmentStatus($shipmentStatus);
            }
            $order->setShipmentStatus($shipmentStatus);
            $this->orderRepo->saveAndCommit($order);
        }
    }


    public function handleOrderVoidedLogic (Shipment $shipment)
    {
        $shipmentStatusValidation       = new ShipmentStatusValidation();
        $orderCollection                = new ArrayCollection();

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
            $shipmentStatus             = $shipmentStatusValidation->getPending();
            foreach ($order->getItems() AS $orderItem)
            {
                if ($orderItem->getQuantityShipped() > 0)
                    $shipmentStatus         = $shipmentStatusValidation->getPartiallyShipped();

                $orderItem->setShipmentStatus($shipmentStatus);
                $this->orderItemRepo->saveAndCommit($orderItem);
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
        $easyPostService                = new EasyPostService($this->integratedShippingApi);
        $easyPostShipment               = $easyPostService->purchasePostage($rate->getExternalShipmentId(), $rate->getExternalId());
        $postage                        = new Postage();
        $postage->setShipment($shipment);
        $postage->setLabelPath($easyPostShipment->getPostageLabel()->getLabelUrl());
        $postage->setTrackingNumber($easyPostShipment->getTrackingCode());
        $postage->setExternalId($easyPostShipment->getPostageLabel()->getId());
        $shipment->setPostage($postage);

        return $shipment;
    }

    /**
     * @param   Shipment $shipment
     * @return  Shipment
     */
    private function voidEasyPost (Shipment $shipment)
    {
        $easyPostService           = new EasyPostService($this->integratedShippingApi);
        $easyPostService->voidPostage($shipment->getPostage()->getRate()->getExternalShipmentId());

        return $shipment;
    }
}