<?php

namespace App\Models\OMS;


use App\Models\BaseModel;
use App\Models\CMS\Client;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ProductAlias extends BaseModel
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var CRMSource
     */
    protected $crmSource;

    /**
     * Shopify product id
     * @var string
     */
    protected $externalId;

    /**
     * Shopify product created_at
     * @var \DateTime
     */
    protected $externalCreatedAt;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * ProductAlias constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->externalCreatedAt        = new \DateTime();  // Default it so the field doesn't have to be nullable

        $this->client                   = AU::get($data['client']);
        $this->product                  = AU::get($data['product']);
        $this->crmSource                = AU::get($data['crmSource']);
        $this->externalId               = AU::get($data['externalId']);
    }

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['createdAt']            = $this->createdAt;
        $object['crmSource']            = $this->crmSource->jsonSerialize();
        $object['product']              = $this->product->jsonSerialize();
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
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return CRMSource
     */
    public function getCrmSource()
    {
        return $this->crmSource;
    }

    /**
     * @param CRMSource $crmSource
     */
    public function setCrmSource($crmSource)
    {
        $this->crmSource = $crmSource;
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}