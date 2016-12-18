<?php

namespace App\Services\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

class GetShopifyProductCount implements \JsonSerializable
{

    /**
     * Filter by product vendor
     * @var string|null
     */
    protected $vendor;

    /**
     * Filter by product type
     * @var string|null
     */
    protected $product_type;

    /**
     * Filter by collection id
     * @var string|null
     */
    protected $collection_id;

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
     * Show products published after date (format: 2014-04-25T16:15:47-04:00)
     * @var string|null
     */
    protected $published_at_min;

    /**
     * Show products published before date (format: 2014-04-25T16:15:47-04:00)
     * @var string|null
     */
    protected $published_at_max;

    /**
     * published - Show only published products
     * unpublished - Show only unpublished products
     * any - Show all products (default)
     * @var string|null
     */
    protected $published_status;


    /**
     * GetShopifyProducts constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->vendor                   = AU::get($data['vendor']);
        $this->product_type             = AU::get($data['product_type']);
        $this->collection_id            = AU::get($data['collection_id']);
        $this->created_at_min           = AU::get($data['created_at_min']);
        $this->created_at_max           = AU::get($data['created_at_max']);
        $this->updated_at_min           = AU::get($data['updated_at_min']);
        $this->updated_at_max           = AU::get($data['updated_at_max']);
        $this->published_at_min         = AU::get($data['published_at_min']);
        $this->published_at_max         = AU::get($data['published_at_max']);
        $this->published_status         = AU::get($data['published_status'], 'any');
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['vendor']               = $this->vendor;
        $object['product_type']         = $this->product_type;
        $object['collection_id']        = $this->collection_id;
        $object['created_at_min']       = $this->created_at_min;
        $object['created_at_max']       = $this->created_at_max;
        $object['updated_at_min']       = $this->updated_at_min;
        $object['updated_at_max']       = $this->updated_at_max;
        $object['published_at_min']     = $this->published_at_min;
        $object['published_at_max']     = $this->published_at_max;
        $object['published_status']     = $this->published_status;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param null|string $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * @return null|string
     */
    public function getProductType()
    {
        return $this->product_type;
    }

    /**
     * @param null|string $product_type
     */
    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
    }

    /**
     * @return null|string
     */
    public function getCollectionId()
    {
        return $this->collection_id;
    }

    /**
     * @param null|string $collection_id
     */
    public function setCollectionId($collection_id)
    {
        $this->collection_id = $collection_id;
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
    public function getPublishedAtMin()
    {
        return $this->published_at_min;
    }

    /**
     * @param null|string $published_at_min
     */
    public function setPublishedAtMin($published_at_min)
    {
        $this->published_at_min = $published_at_min;
    }

    /**
     * @return null|string
     */
    public function getPublishedAtMax()
    {
        return $this->published_at_max;
    }

    /**
     * @param null|string $published_at_max
     */
    public function setPublishedAtMax($published_at_max)
    {
        $this->published_at_max = $published_at_max;
    }

    /**
     * @return null|string
     */
    public function getPublishedStatus()
    {
        return $this->published_status;
    }

    /**
     * @param null|string $published_status
     */
    public function setPublishedStatus($published_status)
    {
        $this->published_status = $published_status;
    }

}