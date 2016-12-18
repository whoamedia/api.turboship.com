<?php

namespace App\Integrations\Shopify\Models\Responses;


use App\Integrations\Shopify\Models\Requests\CreateShopifyMetaField;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyMetaField extends CreateShopifyMetaField implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $owner_id;

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
    protected $owner_resource;


    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->id                       = AU::get($data['id']);
        $this->owner_id                 = AU::get($data['owner_id']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
        $this->owner_resource           = AU::get($data['owner_resource']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['id']                   = $this->id;
        $object['owner_id']             = $this->owner_id;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;
        $object['owner_resource']       = $this->owner_resource;

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
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param int $owner_id
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
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
    public function getOwnerResource()
    {
        return $this->owner_resource;
    }

    /**
     * @param string $owner_resource
     */
    public function setOwnerResource($owner_resource)
    {
        $this->owner_resource = $owner_resource;
    }

}