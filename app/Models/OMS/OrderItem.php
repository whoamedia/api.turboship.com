<?php

namespace App\Models\OMS;


use App\Models\Shipments\ShipmentItem;
use App\Models\Support\ShipmentStatus;
use App\Models\Support\Validation\ShipmentStatusValidation;
use App\Utilities\OrderStatusUtility;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class OrderItem implements \JsonSerializable
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
     * Shopify product_id
     * @var string|null
     */
    protected $externalProductId;

    /**
     * Shopify variant_id
     * @var string|null
     */
    protected $externalVariantId;

    /**
     * Shopify variant_title
     * @var string|null
     */
    protected $externalVariantTitle;

    /**
     * Shopify sku
     * @var string
     */
    protected $sku;

    /**
     * Shopify quantity
     * @var int
     */
    protected $quantityPurchased;

    /**
     * Shopify fulfillable_quantity
     * @var int
     */
    protected $quantityToFulfill;

    /**
     * @var int
     */
    protected $quantityShipped;

    /**
     * Shopify price
     * @var float
     */
    protected $basePrice;

    /**
     * Shopify total_discount
     * @var float
     */
    protected $totalDiscount;

    /**
     * Shopify total of each tax_lines->price
     * @var float
     */
    protected $totalTaxes;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var Variant|null
     */
    protected $variant;

    /**
     * @var OrderStatus
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var ArrayCollection
     */
    protected $shipmentItems;

    /**
     * @var ShipmentStatus
     */
    protected $shipmentStatus;

    /**
     * OrderItem constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->shipmentItems            = new ArrayCollection();

        $this->externalId               = AU::get($data['externalId']);
        $this->externalProductId        = AU::get($data['externalProductId']);
        $this->externalVariantId        = AU::get($data['externalVariantId']);
        $this->externalVariantTitle     = AU::get($data['externalVariantTitle']);
        $this->sku                      = AU::get($data['sku']);
        $this->quantityPurchased        = AU::get($data['quantityPurchased']);
        $this->quantityToFulfill        = AU::get($data['quantityToFulfill']);
        $this->quantityShipped          = AU::get($data['quantityShipped'], 0);
        $this->basePrice                = AU::get($data['basePrice']);
        $this->totalDiscount            = AU::get($data['totalDiscount']);
        $this->totalTaxes               = AU::get($data['totalTaxes']);
        $this->status                   = AU::get($data['status']);
        $this->shipmentStatus           = AU::get($data['shipmentStatus']);
        $this->order                    = AU::get($data['order']);
        $this->variant                  = AU::get($data['variant']);

        if (is_null($this->status))
        {
            $orderStatusUtility         = new OrderStatusUtility();
            $this->setStatus($orderStatusUtility->getCreated());
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
        $object['sku']                  = $this->sku;
        $object['quantityPurchased']    = $this->quantityPurchased;
        $object['quantityToFulfill']    = $this->quantityToFulfill;
        $object['quantityShipped']      = $this->quantityShipped;
        $object['basePrice']            = $this->basePrice;
        $object['totalDiscount']        = $this->totalDiscount;
        $object['totalTaxes']           = $this->totalTaxes;
        $object['status']               = $this->status->jsonSerialize();
        $object['shipmentStatus']       = $this->shipmentStatus->jsonSerialize();
        $object['variant']              = is_null($this->variant) ? null : $this->variant->jsonSerialize();
        $object['externalId']           = $this->externalId;
        $object['externalProductId']    = $this->externalProductId;
        $object['externalVariantId']    = $this->externalVariantId;

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
     * @return null|string
     */
    public function getExternalProductId()
    {
        return $this->externalProductId;
    }

    /**
     * @param null|string $externalProductId
     */
    public function setExternalProductId($externalProductId)
    {
        $this->externalProductId = $externalProductId;
    }

    /**
     * @return null|string
     */
    public function getExternalVariantId()
    {
        return $this->externalVariantId;
    }

    /**
     * @param null|string $externalVariantId
     */
    public function setExternalVariantId($externalVariantId)
    {
        $this->externalVariantId = $externalVariantId;
    }

    /**
     * @return null|string
     */
    public function getExternalVariantTitle()
    {
        return $this->externalVariantTitle;
    }

    /**
     * @param null|string $externalVariantTitle
     */
    public function setExternalVariantTitle($externalVariantTitle)
    {
        $this->externalVariantTitle = $externalVariantTitle;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return int
     */
    public function getQuantityPurchased()
    {
        return $this->quantityPurchased;
    }

    /**
     * @param int $quantityPurchased
     */
    public function setQuantityPurchased($quantityPurchased)
    {
        $this->quantityPurchased = $quantityPurchased;
    }

    /**
     * @return int
     */
    public function getQuantityToFulfill()
    {
        return $this->quantityToFulfill;
    }

    /**
     * @param int $quantityToFulfill
     */
    public function setQuantityToFulfill($quantityToFulfill)
    {
        $this->quantityToFulfill = $quantityToFulfill;
    }

    /**
     * @return int
     */
    public function getQuantityShipped()
    {
        return $this->quantityShipped;
    }

    /**
     * @param int $quantityShipped
     */
    public function setQuantityShipped($quantityShipped)
    {
        $this->quantityShipped = $quantityShipped;
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
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return Variant|null
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @param Variant|null $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
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
    public function setStatus($status)
    {
        $this->status = $status;

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
     * @return ShipmentItem[]
     */
    public function getShipmentItems ()
    {
        return $this->shipmentItems->toArray();
    }

    /**
     * @param ShipmentItem  $shipmentItem
     * @param int           $quantity
     */
    public function addShipmentItem (ShipmentItem $shipmentItem, $quantity)
    {
        $shipmentItem->setOrderItem($this);
        $shipmentItem->setQuantity($quantity);
        $this->quantityToFulfill        -= $quantity;
        $this->shipmentItems->add($shipmentItem);
    }

    /**
     * @return int
     */
    public function getRemainingQuantityToFulfill ()
    {
        $shipmentQuantity               = 0;
        foreach ($this->getShipmentItems() AS $shipmentItem)
            $shipmentQuantity           += $shipmentItem->getQuantity();

        return $shipmentQuantity;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Is it safe for us to add the OrderItem to a Shipment ?
     * @return bool
     */
    public function canAddToShipment ()
    {
        if (is_null($this->variant))
            return false;
        else if ($this->getQuantityToFulfill() == 0)
            return false;
        else if ($this->order->getStatus()->getId() != OrderStatusUtility::PENDING_FULFILLMENT_ID)
            return false;
        else if ($this->getRemainingQuantityToFulfill() >= $this->getQuantityToFulfill())
            return false;


        return true;
    }

}