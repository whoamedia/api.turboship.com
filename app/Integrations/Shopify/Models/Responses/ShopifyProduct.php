<?php

namespace App\Integrations\Shopify\Models\Responses;


use App\Integrations\Shopify\Models\Requests\CreateShopifyProduct;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyProduct extends CreateShopifyProduct implements \JsonSerializable
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
     * @var string|null
     */
    protected $published_at;

    /**
     * @var ShopifyProductImage[]
     */
    protected $images;

    /**
     * @var ShopifyProductImage|null
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

        $this->images                   = [];
        $images                         = AU::get($data['images'], []);
        foreach ($images AS $item)
        {
            $this->images[]             = new ShopifyProductImage($item);
        }

        $this->image                    = AU::get($data['image']);
        if (!is_null($this->image))
            $this->image                = new ShopifyProductImage($this->image);
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

        $object['images']               = [];
        foreach ($this->images AS $image)
        {
            $object['images'][]         = $image->jsonSerialize();
        }

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
     * @return string|null
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param string|null $published_at
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
    }

    /**
     * @return ShopifyProductImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ShopifyProductImage[] $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return ShopifyProductImage|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ShopifyProductImage|null $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

}