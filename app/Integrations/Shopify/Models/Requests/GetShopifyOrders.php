<?php

namespace App\Integrations\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see     https://help.shopify.com/api/reference/order#index
 * Class GetShopifyOrders
 * @package App\Integrations\Shopify\Models\Requests
 */
class GetShopifyOrders implements \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * (default: 50) (maximum: 250)
     * @var int
     */
    protected $limit;

    /**
     * (default: 1)
     * @var int
     */
    protected $page;

    /**
     * @var bool|null
     */
    protected $test;

    /**
     * Restrict results to after the specified ID
     * @var int|null
     */
    protected $since_id;

    /**
     * @var string|null
     */
    protected $created_at_min;

    /**
     * @var string|null
     */
    protected $created_at_max;

    /**
     * @var string|null
     */
    protected $updated_at_min;

    /**
     * @var string|null
     */
    protected $updated_at_max;

    /**
     * @var string|null
     */
    protected $processed_at_min;

    /**
     * @var string|null
     */
    protected $processed_at_max;

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
     * Format is: "<field> <direction>"
     * Field values:        created_at, updated_at, processed_at
     * Direction values:    asc, desc
     * @var string|null
     */
    protected $order;


    /**
     * GetShopifyOrders constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->limit                    = AU::get($data['limit'], 50);
        $this->page                     = AU::get($data['page'], 1);
        $this->test                     = AU::get($data['test']);
        $this->since_id                 = AU::get($data['since_id']);
        $this->created_at_min           = AU::get($data['created_at_min']);
        $this->created_at_max           = AU::get($data['created_at_max']);
        $this->updated_at_min           = AU::get($data['updated_at_min']);
        $this->updated_at_max           = AU::get($data['updated_at_max']);
        $this->processed_at_min         = AU::get($data['processed_at_min']);
        $this->processed_at_max         = AU::get($data['processed_at_max']);
        $this->status                   = AU::get($data['status'], 'open');
        $this->financial_status         = AU::get($data['financial_status'], 'any');
        $this->fulfillment_status       = AU::get($data['fulfillment_status'], 'any');
        $this->order                    = AU::get($data['order']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['ids']                  = $this->ids;
        $object['limit']                = $this->limit;
        $object['page']                 = $this->page;
        $object['test']                 = $this->test;
        $object['since_id']             = $this->since_id;
        $object['created_at_min']       = $this->created_at_min;
        $object['created_at_max']       = $this->created_at_max;
        $object['updated_at_min']       = $this->updated_at_min;
        $object['updated_at_max']       = $this->updated_at_max;
        $object['processed_at_min']     = $this->processed_at_min;
        $object['status']               = $this->status;
        $object['financial_status']     = $this->financial_status;
        $object['fulfillment_status']   = $this->fulfillment_status;
        $object['order']                = $this->order;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param null|string $ids
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return bool|null
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param bool|null $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return int|null
     */
    public function getSinceId()
    {
        return $this->since_id;
    }

    /**
     * @param int|null $since_id
     */
    public function setSinceId($since_id)
    {
        $this->since_id = $since_id;
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
     * @return null|string
     */
    public function getProcessedAtMin()
    {
        return $this->processed_at_min;
    }

    /**
     * @param null|string $processed_at_min
     */
    public function setProcessedAtMin($processed_at_min)
    {
        $this->processed_at_min = $processed_at_min;
    }

    /**
     * @return null|string
     */
    public function getProcessedAtMax()
    {
        return $this->processed_at_max;
    }

    /**
     * @param null|string $processed_at_max
     */
    public function setProcessedAtMax($processed_at_max)
    {
        $this->processed_at_max = $processed_at_max;
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

    /**
     * @return null|string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param null|string $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

}