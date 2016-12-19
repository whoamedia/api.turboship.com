<?php

namespace App\Models\OMS;


use App\Models\BaseModel;
use App\Models\CMS\Client;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Product extends BaseModel
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var \DateTime
     */
    protected $externalCreatedAt;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->externalCreatedAt        = new \DateTime();

        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
        $this->sku                      = AU::get($data['sku']);
        $this->externalId               = AU::get($data['externalId']);
        $this->client                   = AU::get($data['client']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        $object['client']               = $this->getClient()->jsonSerialize();
        $object['description']          = $this->description;
        $object['createdAt']            = $this->createdAt;
        $object['externalId']           = $this->externalId;
        $object['externalCreatedAt']    = $this->externalCreatedAt;

        return $object;
    }

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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