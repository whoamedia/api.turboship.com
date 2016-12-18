<?php

namespace App\Services\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

class CreateShopifyMetaField implements \JsonSerializable
{

    /**
     * Identifier for the metafield (maximum of 30 characters).
     * @var string
     */
    protected $key;

    /**
     * Information to be stored as metadata.
     * @var string
     */
    protected $value;

    /**
     * States whether the information in the value is stored as a 'string' or 'integer.'
     * @var string
     */
    protected $value_type;

    /**
     * Container for a set of metadata.
     * Namespaces help distinguish between metadata you created and metadata created by another individual with a similar namespace (maximum of 20 characters).
     * @var string
     */
    protected $namespace;

    /**
     * Additional information about the metafield.
     * @var string|null
     */
    protected $description;


    /**
     * MetaField constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->key                      = AU::get($data['key']);
        $this->value                    = AU::get($data['value']);
        $this->value_type               = AU::get($data['value_type']);
        $this->namespace                = AU::get($data['namespace']);
        $this->description              = AU::get($data['description']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['key']                  = $this->key;
        $object['value']                = $this->value;
        $object['value_type']           = $this->value_type;
        $object['namespace']            = $this->namespace;
        $object['description']          = $this->description;

        return $object;
    }


    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValueType()
    {
        return $this->value_type;
    }

    /**
     * @param string $value_type
     */
    public function setValueType($value_type)
    {
        $this->value_type = $value_type;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}