<?php

namespace App\Integrations\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see     https://help.shopify.com/api/reference/order#count
 * Class GetShopifyOrderCount
 * @package App\Integrations\Shopify\Models\Requests
 */
class GetShopifyOrderCount implements \JsonSerializable
{

    /**
     * Show products created after date (format: 2014-04-25T16:15:47-04:00)
     * @var string|null
     */
    protected $created_at_min;

    /**
     * Show products created before date (format: 2014-04-25T16:15:47-04:00)
     * @var string|null
     */
    protected $created_at_max;

    /**
     * Show products last updated after date (format: 2014-04-25T16:15:47-04:00)
     * @var string|null
     */
    protected $updated_at_min;

    /**
     * Show products last updated before date (format: 2014-04-25T16:15:47-04:00)
     * @var string|null
     */
    protected $updated_at_max;

    /**
     * open - All open orders (default)
     * closed - Show only closed orders
     * cancelled - Show only cancelled orders
     * any - Any order status
     * @var string
     */
    protected $status;

    /**
     * authorized - Show only authorized orders
     * pending - Show only pending orders
     * paid - Show only paid orders
     * partially_paid - Show only partially paid orders
     * refunded - Show only refunded orders
     * voided - Show only voided orders
     * partially_refunded - Show only partially_refunded orders
     * any - Show all authorized, pending, and paid orders (default). This is a filter, not a value.
     * unpaid - Show all authorized, or partially_paid orders. This is a filter, not a value.
     * @var string
     */
    protected $financial_status;


    /**
     * shipped - Show orders that have been shipped
     * partial - Show partially shipped orders
     * unshipped - Show orders that have not yet been shipped
     * any - Show orders with any fulfillment_status. (default)
     * @var string
     */
    protected $fulfillment_status;




    /**
     * GetShopifyProducts constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->created_at_min           = AU::get($data['created_at_min']);
        $this->created_at_max           = AU::get($data['created_at_max']);
        $this->updated_at_min           = AU::get($data['updated_at_min']);
        $this->updated_at_max           = AU::get($data['updated_at_max']);
        $this->status                   = AU::get($data['status']);
        $this->financial_status         = AU::get($data['financial_status']);
        $this->fulfillment_status       = AU::get($data['fulfillment_status']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['created_at_min']       = $this->created_at_min;
        $object['created_at_max']       = $this->created_at_max;
        $object['updated_at_min']       = $this->updated_at_min;
        $object['updated_at_max']       = $this->updated_at_max;
        $object['status']               = $this->status;
        $object['financial_status']     = $this->financial_status;
        $object['fulfillment_status']   = $this->fulfillment_status;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getCreatedAtMin()
    {
        return $this->created_at_min;
    }

    /**
     * @param null|string $created_at_min
     */
    public function setCreatedAtMin($created_at_min)
    {
        $this->created_at_min = $created_at_min;
    }

    /**
     * @return null|string
     */
    public function getCreatedAtMax()
    {
        return $this->created_at_max;
    }

    /**
     * @param null|string $created_at_max
     */
    public function setCreatedAtMax($created_at_max)
    {
        $this->created_at_max = $created_at_max;
    }

    /**
     * @return null|string
     */
    public function getUpdatedAtMin()
    {
        return $this->updated_at_min;
    }

    /**
     * @param null|string $updated_at_min
     */
    public function setUpdatedAtMin($updated_at_min)
    {
        $this->updated_at_min = $updated_at_min;
    }

    /**
     * @return null|string
     */
    public function getUpdatedAtMax()
    {
        return $this->updated_at_max;
    }

    /**
     * @param null|string $updated_at_max
     */
    public function setUpdatedAtMax($updated_at_max)
    {
        $this->updated_at_max = $updated_at_max;
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
    public function getFinancialStatus()
    {
        return $this->financial_status;
    }

    /**
     * @param string $financial_status
     */
    public function setFinancialStatus($financial_status)
    {
        $this->financial_status = $financial_status;
    }

    /**
     * @return string
     */
    public function getFulfillmentStatus()
    {
        return $this->fulfillment_status;
    }

    /**
     * @param string $fulfillment_status
     */
    public function setFulfillmentStatus($fulfillment_status)
    {
        $this->fulfillment_status = $fulfillment_status;
    }

}