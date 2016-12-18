<?php

namespace App\Integrations\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyProductImage implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $product_id;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    /**
     * @var string
     */
    protected $src;

    /**
     * @var array
     */
    protected $variant_ids;


    /**
     * ProductImage constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->product_id               = AU::get($data['product_id']);
        $this->position                 = AU::get($data['position']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
        $this->src                      = AU::get($data['src']);
        $this->variant_ids              = AU::get($data['variant_ids']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['product_id']           = $this->product_id;
        $object['position']             = $this->position;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;
        $object['src']                  = $this->src;
        $object['variant_ids']          = $this->variant_ids;

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

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param string $src
     */
    public function setSrc($src)
    {
        $this->src = $src;
    }

    /**
     * @return array
     */
    public function getVariantIds()
    {
        return $this->variant_ids;
    }

    /**
     * @param array $variant_ids
     */
    public function setVariantIds($variant_ids)
    {
        $this->variant_ids = $variant_ids;
    }

}