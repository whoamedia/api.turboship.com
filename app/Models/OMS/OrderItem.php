<?php

namespace App\Models\OMS;


use App\Utilities\OrderStatusUtility;
use jamesvweston\Utilities\ArrayUtil AS AU;

class OrderItem implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var string|null
     */
    protected $externalProductId;

    /**
     * @var string|null
     */
    protected $externalVariantId;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var int
     */
    protected $quantityPurchased;

    /**
     * @var int
     */
    protected $quantityToFulfill;

    /**
     * @var float
     */
    protected $basePrice;

    /**
     * @var float
     */
    protected $totalDiscount;

    /**
     * @var float
     */
    protected $totalTaxes;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderStatus
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * OrderItem constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->externalId               = AU::get($data['externalId']);
        $this->externalProductId        = AU::get($data['externalProductId']);
        $this->externalVariantId        = AU::get($data['externalVariantId']);
        $this->sku                      = AU::get($data['sku']);
        $this->quantityPurchased        = AU::get($data['quantityPurchased']);
        $this->quantityToFulfill        = AU::get($data['quantityToFulfill']);
        $this->basePrice                = AU::get($data['basePrice']);
        $this->totalDiscount            = AU::get($data['totalDiscount']);
        $this->totalTaxes               = AU::get($data['totalTaxes']);
        $this->status                   = AU::get($data['status']);
        $this->order                    = AU::get($data['order']);

        if (is_null($this->status))
        {
            $orderStatusUtility         = new OrderStatusUtility();
            $this->setStatus($orderStatusUtility->getCreated());
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['externalId']           = $this->externalId;
        $object['externalProductId']    = $this->externalProductId;
        $object['externalVariantId']    = $this->externalVariantId;
        $object['sku']                  = $this->sku;
        $object['quantityPurchased']    = $this->quantityPurchased;
        $object['quantityToFulfill']    = $this->quantityToFulfill;
        $object['basePrice']            = $this->basePrice;
        $object['totalDiscount']        = $this->totalDiscount;
        $object['totalTaxes']           = $this->totalTaxes;
        $object['status']               = $this->status->jsonSerialize();

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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}