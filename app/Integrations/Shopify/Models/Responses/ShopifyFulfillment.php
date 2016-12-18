<?php

namespace App\Integrations\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyFulfillment implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|array|null
     */
    protected $line_items;

    /**
     * @var int
     */
    protected $order_id;

    /**
     * A textfield with information about the receipt.
     * @var string
     */
    protected $receipt;

    /**
     * The status of the fulfillment.
     * @var string
     */
    protected $status;

    /**
     * The name of the shipping company.
     * @var string
     */
    protected $tracking_company;

    /**
     * The shipping number, provided by the shipping company.
     * @var string
     */
    protected $tracking_number;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->line_items               = AU::get($data['line_items']);
        $this->order_id                 = AU::get($data['order_id']);
        $this->receipt                  = AU::get($data['receipt']);
        $this->status                   = AU::get($data['status']);
        $this->tracking_company         = AU::get($data['tracking_company']);
        $this->tracking_number          = AU::get($data['tracking_number']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['line_items']           = $this->line_items;
        $object['order_id']             = $this->order_id;
        $object['receipt']              = $this->receipt;
        $object['status']               = $this->status;
        $object['tracking_company']     = $this->tracking_company;
        $object['tracking_number']      = $this->tracking_number;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;

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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return array|null|string
     */
    public function getLineItems()
    {
        return $this->line_items;
    }

    /**
     * @param array|null|string $line_items
     */
    public function setLineItems($line_items)
    {
        $this->line_items = $line_items;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param int $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * @return string
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @param string $receipt
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTrackingCompany()
    {
        return $this->tracking_company;
    }

    /**
     * @param string $tracking_company
     */
    public function setTrackingCompany($tracking_company)
    {
        $this->tracking_company = $tracking_company;
    }

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->tracking_number;
    }

    /**
     * @param string $tracking_number
     */
    public function setTrackingNumber($tracking_number)
    {
        $this->tracking_number = $tracking_number;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

}