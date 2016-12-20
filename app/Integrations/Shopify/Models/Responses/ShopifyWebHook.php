<?php

namespace App\Integrations\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyWebHook implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $metafield_namespaces;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->topic                    = AU::get($data['topic']);
        $this->address                  = AU::get($data['address']);
        $this->format                   = AU::get($data['format']);
        $this->fields                   = AU::get($data['fields']);
        $this->metafield_namespaces     = AU::get($data['metafield_namespaces']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['topic']                = $this->topic;
        $object['address']              = $this->address;
        $object['format']               = $this->format;
        $object['fields']               = $this->fields;
        $object['metafield_namespaces'] = $this->metafield_namespaces;
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
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getMetafieldNamespaces()
    {
        return $this->metafield_namespaces;
    }

    /**
     * @param array $metafield_namespaces
     */
    public function setMetafieldNamespaces($metafield_namespaces)
    {
        $this->metafield_namespaces = $metafield_namespaces;
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