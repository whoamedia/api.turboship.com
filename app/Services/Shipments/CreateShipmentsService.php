<?php

namespace App\Services\Shipments;


use App\Models\CMS\Client;
use App\Models\OMS\Order;
use App\Models\Shipments\Shipment;
use App\Models\Shipments\ShipmentAlgorithm;
use App\Models\Shipments\ShipmentItem;
use App\Models\Shipments\Shipper;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Repositories\Doctrine\Shipments\ShipmentAlgorithmRepository;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Utilities\OrderStatusUtility;
use App\Utilities\ShipmentAlgorithmUtility;
use EntityManager;

class CreateShipmentsService
{

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var ShipmentRepository
     */
    private $shipmentRepo;

    /**
     * @var ShipmentAlgorithmRepository
     */
    private $shipmentAlgorithmRepo;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Shipper
     */
    private $shipper;

    /**
     * @var ShipmentAlgorithm
     */
    private $shipmentAlgorithm;


    public function __construct (Client $client, Shipper $shipper)
    {
        $this->client                   = $client;
        $this->shipper                  = $shipper;
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $this->shipmentAlgorithmRepo    = EntityManager::getRepository('App\Models\Shipments\ShipmentAlgorithm');
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
    }

    /**
     * @param   Order[]|null    $orders
     * @return  Shipment[]
     */
    public function runOnePerOrder ($orders = null)
    {
        if (is_null($orders))
            $orders                     = $this->getPendingFulfillmentOrders();

        $this->shipmentAlgorithm        = $this->shipmentAlgorithmRepo->getOneById(ShipmentAlgorithmUtility::ONE_SHIPMENT_PER_ORDER);
        $shipments                      = [];
        foreach ($orders AS $order)
        {
            $shipment                   = new Shipment();
            $shipment->setShipper($this->shipper);
            $shipment->setFromAddress($this->shipper->getAddress());
            $shipment->setReturnAddress($this->shipper->getReturnAddress());
            $shipment->setToAddress($order->getShippingAddress());

            foreach ($order->getItems() AS $orderItem)
            {
                if (!$orderItem->canAddToShipment())
                    continue;

                $shipmentItem           = new ShipmentItem();
                $shipmentItem->setQuantity($orderItem->getQuantityToFulfill());
                $shipmentItem->setOrderItem($orderItem);
                //  $orderItem->addShipmentItem($shipmentItem, $orderItem->getQuantityToFulfill());
                $shipment->addItem($shipmentItem);
                $shipments[]            = $shipment;
            }
        }


        return $shipments;
    }

    /**
     * @return  Order[]
     */
    public function getPendingFulfillmentOrders ()
    {
        $orderQuery     = [
            'clientIds'                 => $this->client->getId(),
            'statusIds'                 => OrderStatusUtility::PENDING_FULFILLMENT_ID
        ];

        return $this->orderRepo->where($orderQuery);
    }

}