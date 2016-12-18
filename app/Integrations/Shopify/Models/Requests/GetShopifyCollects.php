<?php

namespace App\Integrations\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

class GetShopifyCollects implements \JsonSerializable
{

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
     * @var int|null
     */
    protected $product_id;

    /**
     * @var int|null
     */
    protected $collection_id;


    /**
     * GetShopifyCollects constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->limit                    = AU::get($data['limit'], 50);
        $this->page                     = AU::get($data['page'], 1);
        $this->product_id               = AU::get($data['product_id']);
        $this->collection_id            = AU::get($data['collection_id']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['limit']                = $this->limit;
        $object['page']                 = $this->page;
        $object['product_id']           = $this->product_id;
        $object['collection_id']        = $this->collection_id;

        return $object;
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
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param int|null $product_id
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @return int|null
     */
    public function getCollectionId()
    {
        return $this->collection_id;
    }

    /**
     * @param int|null $collection_id
     */
    public function setCollectionId($collection_id)
    {
        $this->collection_id = $collection_id;
    }

}