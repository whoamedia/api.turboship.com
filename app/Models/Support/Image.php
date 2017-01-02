<?php

namespace App\Models\Support;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Image implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * Shopify src
     * @var string
     */
    protected $path;

    /**
     * Shopify id
     * @var string
     */
    protected $externalId;

    /**
     * @var Source
     */
    protected $source;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Shopify created_at
     * @var \DateTime
     */
    protected $externalCreatedAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->externalCreatedAt        = new \DateTime();

        $this->path                     = AU::get($data['path']);
        $this->externalId               = AU::get($data['externalId']);
        $this->source                   = AU::get($data['source']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['path']                 = $this->path;
        $object['source']               = $this->source->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['externalId']           = $this->externalId;
        $object['externalCreatedAt']    = $this->externalCreatedAt;

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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param Source $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getExternalCreatedAt()
    {
        return $this->externalCreatedAt;
    }

    /**
     * @param \DateTime $externalCreatedAt
     */
    public function setExternalCreatedAt($externalCreatedAt)
    {
        $this->externalCreatedAt = $externalCreatedAt;
    }

}