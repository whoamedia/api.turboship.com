<?php

namespace App\Services\Shopify\Models\Responses;


use App\Services\Shopify\Models\Requests\CreateShopifyProduct;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Product extends CreateShopifyProduct implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

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
    protected $published_at;

    /**
     * @var ProductImage|null
     */
    protected $image;


    /**
     * Product constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->id                       = AU::get($data['id']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
        $this->published_at             = AU::get($data['published_at']);

        $this->image                    = AU::get($data['image']);
        if (!is_null($this->image))
            $this->image                = new ProductImage($this->image);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['id']                   = $this->id;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;
        $object['published_at']         = $this->published_at;

        $object['image']                = !is_null($this->image) ? $this->image->jsonSerialize() : null;

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
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param string $published_at
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
    }

    /**
     * @return ProductImage|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ProductImage|null $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

}