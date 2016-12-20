<?php

namespace App\Integrations\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

class GetShopifyWebHooks implements \JsonSerializable
{

    /**
     * A comma-separated list of product ids
     * @var string|null
     */
    protected $ids;

    /**
     * Use this parameter to retrieve only webhooks that possess the URI where the webhook sends the POST request when the event occurs.
     * @var string|null
     */
    protected $address;

    /**
     * @var string|null
     */
    protected $topic;

    /**
     * Amount of results
     * (default: 50) (maximum: 250)
     * @var int
     */
    protected $limit;

    /**
     * Page to show
     * (default: 1)
     * @var int
     */
    protected $page;

    /**
     * Restrict results to after the specified ID
     * @var int|null
     */
    protected $since_id;

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
     * comma-separated list of fields to include in the response
     * @var string|null
     */
    protected $fields;


    /**
     * GetShopifyProducts constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->address                  = AU::get($data['address']);
        $this->topic                    = AU::get($data['topic']);
        $this->limit                    = AU::get($data['limit'], 50);
        $this->page                     = AU::get($data['page'], 1);
        $this->since_id                 = AU::get($data['since_id']);
        $this->created_at_min           = AU::get($data['created_at_min']);
        $this->created_at_max           = AU::get($data['created_at_max']);
        $this->updated_at_min           = AU::get($data['updated_at_min']);
        $this->updated_at_max           = AU::get($data['updated_at_max']);
        $this->fields                   = AU::get($data['fields']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['ids']                  = $this->ids;
        $object['address']              = $this->address;
        $object['topic']                = $this->topic;
        $object['limit']                = $this->limit;
        $object['page']                 = $this->page;
        $object['since_id']             = $this->since_id;
        $object['created_at_min']       = $this->created_at_min;
        $object['created_at_max']       = $this->created_at_max;
        $object['updated_at_min']       = $this->updated_at_min;
        $object['updated_at_max']       = $this->updated_at_max;
        $object['fields']               = $this->fields;

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
     * @return null|string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param null|string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return null|string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param null|string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
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
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param null|string $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

}