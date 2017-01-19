<?php

namespace App\Models\OMS;


use App\Models\CMS\Client;
use App\Models\Locations\Address;
use App\Models\Support\ShipmentStatus;
use App\Models\Support\Source;
use App\Models\Support\Validation\ShipmentStatusValidation;
use App\Utilities\OrderStatusUtility;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Order implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * Shopify id
     * @var string
     */
    protected $externalId;

    /**
     * Shopify subtotal_price
     * @var float
     */
    protected $basePrice;

    /**
     * Shopify total_discounts
     * @var float
     */
    protected $totalDiscount;

    /**
     * Shopify total_tax
     * @var float
     */
    protected $totalTaxes;

    /**
     * Shopify total_line_items_price
     * @var float
     */
    protected $totalItemsPrice;

    /**
     * Shopify total_price
     * @var float
     */
    protected $totalPrice;

    /**
     * Shopify shipping_address mapped to Address
     * @var Address
     */
    protected $shippingAddress;

    /**
     * Shopify shipping_address
     * @var Address
     */
    protected $providedAddress;

    /**
     * Shopify billing_address
     * @var Address|null
     */
    protected $billingAddress;

    /**
     * @var Source
     */
    protected $source;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var OrderStatus
     */
    protected $status;

    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @var ArrayCollection
     */
    protected $statusHistory;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Shopify created_at
     * @var \DateTime
     */
    protected $externalCreatedAt;

    /**
     * Shopify order total_weight
     * @var float
     */
    protected $externalWeight;

    /**
     * @var ShipmentStatus
     */
    protected $shipmentStatus;

    /**
     * Order constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->items                    = new ArrayCollection();
        $this->statusHistory            = new ArrayCollection();

        $this->externalId               = AU::get($data['externalId']);
        $this->externalWeight           = AU::get($data['externalWeight']);
        $this->basePrice                = AU::get($data['basePrice'], 0.00);
        $this->totalDiscount            = AU::get($data['totalDiscount'], 0.00);
        $this->totalTaxes               = AU::get($data['totalTaxes'], 0.00);
        $this->totalItemsPrice          = AU::get($data['totalItemsPrice'], 0.00);
        $this->totalPrice               = AU::get($data['totalPrice'], 0.00);
        $this->shippingAddress          = AU::get($data['shippingAddress']);
        $this->providedAddress          = AU::get($data['providedAddress']);
        $this->billingAddress           = AU::get($data['billingAddress']);
        $this->source                   = AU::get($data['source']);
        $this->client                   = AU::get($data['client']);
        $this->status                   = AU::get($data['status']);
        $this->shipmentStatus           = AU::get($data['shipmentStatus']);

        /**
         * Default the externalCreatedAt and update it later
         */
        $this->externalCreatedAt        = AU::get($data['externalCreatedAt'], new \DateTime());

        if (is_null($this->status))
        {
            $orderStatusUtility         = new OrderStatusUtility();
            $this->status               = $orderStatusUtility->getCreated();
        }

        if (is_null($this->shipmentStatus))
        {
            $shipmentStatusValidation   = new ShipmentStatusValidation();
            $this->shipmentStatus       = $shipmentStatusValidation->getPending();
        }
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['basePrice']            = $this->basePrice;
        $object['totalDiscount']        = $this->totalDiscount;
        $object['totalTaxes']           = $this->totalTaxes;
        $object['totalItemsPrice']      = $this->totalItemsPrice;
        $object['totalPrice']           = $this->totalPrice;
        $object['shippingAddress']      = $this->shippingAddress->jsonSerialize();
        $object['source']               = $this->source->jsonSerialize();
        $object['client']               = $this->client->jsonSerialize();
        $object['status']               = $this->status->jsonSerialize();
        $object['shipmentStatus']       = $this->shipmentStatus->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['externalId']           = $this->externalId;
        $object['externalWeight']       = $this->externalWeight;
        $object['externalCreatedAt']    = $this->externalCreatedAt;

        $object['items']                = [];
        foreach ($this->getItems() AS $item)
        {
            $object['items'][]          = $item->jsonSerialize();
        }

        return $object;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return float
     */
    public function getExternalWeight()
    {
        return $this->externalWeight;
    }

    /**
     * @param float $externalWeight
     */
    public function setExternalWeight($externalWeight)
    {
        $this->externalWeight = $externalWeight;
    }

    /**
     * @return float
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * @param float $basePrice
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;
    }

    /**
     * @return float
     */
    public function getTotalDiscount()
    {
        return $this->totalDiscount;
    }

    /**
     * @param float $totalDiscount
     */
    public function setTotalDiscount($totalDiscount)
    {
        $this->totalDiscount = $totalDiscount;
    }

    /**
     * @return float
     */
    public function getTotalTaxes()
    {
        return $this->totalTaxes;
    }

    /**
     * @param float $totalTaxes
     */
    public function setTotalTaxes($totalTaxes)
    {
        $this->totalTaxes = $totalTaxes;
    }

    /**
     * @return float
     */
    public function getTotalItemsPrice()
    {
        return $this->totalItemsPrice;
    }

    /**
     * @param float $totalItemsPrice
     */
    public function setTotalItemsPrice($totalItemsPrice)
    {
        $this->totalItemsPrice = $totalItemsPrice;
    }

    /**
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return \DateTime
     */
    public function getExternalCreatedAt()
    {
        return $this->externalCreatedAt;
    }

    /**
     * @param \DateTime $externalCreatedAt
     */
    public function setExternalCreatedAt($externalCreatedAt)
    {
        $this->externalCreatedAt = $externalCreatedAt;
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param Address $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return Address
     */
    public function getProvidedAddress()
    {
        return $this->providedAddress;
    }

    /**
     * @param Address $providedAddress
     */
    public function setProvidedAddress($providedAddress)
    {
        $this->providedAddress = $providedAddress;
    }

    /**
     * @return Address|null
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Address|null $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return Source
     */
    public function getSource ()
    {
        return $this->source;
    }

    /**
     * @param Source $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems ()
    {
        return $this->items->toArray();
    }

    /**
     * @param OrderItem $item
     */
    public function addItem (OrderItem $item)
    {
        $item->setOrder($this);
        $this->items->add($item);
    }

    /**
     * @return OrderStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param OrderStatus $status
     */
    public function addStatus ($status)
    {
        $this->status                   = $status;

        $orderStatusHistory             = new OrderStatusHistory();
        $orderStatusHistory->setOrder($this);
        $orderStatusHistory->setStatus($status);

        $this->statusHistory->add($orderStatusHistory);
    }

    /**
     * @return OrderStatusHistory[]
     */
    public function getStatusHistory()
    {
        return $this->statusHistory->toArray();
    }

    /**
     * @return ShipmentStatus
     */
    public function getShipmentStatus()
    {
        return $this->shipmentStatus;
    }

    /**
     * @param ShipmentStatus $shipmentStatus
     */
    public function setShipmentStatus($shipmentStatus)
    {
        $this->shipmentStatus = $shipmentStatus;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Is it safe for us to update the order?
     * @return bool
     */
    public function canUpdate ()
    {
        return true;
    }

    /**
     * Is it safe for us to cancel the order?
     * @return bool
     */
    public function canCancel ()
    {
        return true;
    }

    /**
     * Is it safe for us to run the OrderApprovalProcess on the order?
     * @return bool
     */
    public function canRunApprovalProcess ()
    {
        return true;
    }


}