<?php

namespace App\Integrations\Shopify\Models\Responses;


use App\Integrations\Shopify\Models\Requests\CreateShopifyCollect;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyCollect extends CreateShopifyCollect implements \JsonSerializable
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
     * Collect constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->id                       = AU::get($data['id']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['id']                   = $this->id;
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