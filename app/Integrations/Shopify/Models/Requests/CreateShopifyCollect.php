<?php

namespace App\Integrations\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

class CreateShopifyCollect implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $collection_id;

    /**
     * States whether or not the collect is featured. Valid values are "true" or "false".
     * @var bool
     */
    protected $featured;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var int
     */
    protected $product_id;

    /**
     * @var string
     */
    protected $sort_value;

    /**
     * Collect constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->collection_id            = AU::get($data['collection_id']);
        $this->featured                 = AU::get($data['featured']);
        $this->position                 = AU::get($data['position']);
        $this->product_id               = AU::get($data['product_id']);
        $this->sort_value               = AU::get($data['sort_value']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['collection_id']        = $this->collection_id;
        $object['featured']             = $this->featured;
        $object['position']             = $this->position;
        $object['product_id']           = $this->product_id;
        $object['sort_value']           = $this->sort_value;

        return $object;
    }

    /**
     * @return int
     */
    public function getCollectionId()
    {
        return $this->collection_id;
    }

    /**
     * @param int $collection_id
     */
    public function setCollectionId($collection_id)
    {
        $this->collection_id = $collection_id;
    }

    /**
     * @return boolean
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * @param boolean $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param int $product_id
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @return string
     */
    public function getSortValue()
    {
        return $this->sort_value;
    }

    /**
     * @param string $sort_value
     */
    public function setSortValue($sort_value)
    {
        $this->sort_value = $sort_value;
    }

}