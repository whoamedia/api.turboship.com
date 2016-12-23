<?php

namespace App\Models\CMS;


use App\Models\BaseModel;
use App\Models\Integrations\ClientECommerceIntegration;
use App\Models\Integrations\ClientShippingIntegration;
use App\Models\OMS\Product;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Client extends BaseModel
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
     * @var Organization
     */
    protected $organization;

    /**
     * @var ArrayCollection
     */
    protected $products;

    /**
     * @var ArrayCollection
     */
    protected $eCommerceIntegrations;

    /**
     * @var ArrayCollection
     */
    protected $shippingIntegrations;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * Client constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->products                 = new ArrayCollection();
        $this->eCommerceIntegrations    = new ArrayCollection();
        $this->shippingIntegrations     = new ArrayCollection();

        $this->name                     = AU::get($data['name']);
        $this->organization             = AU::get($data['organization']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        $object['organization']         = $this->getOrganization()->jsonSerialize();

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
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $product->setClient($this);
        $this->products->add($product);
    }

    /**
     * @return Product[]
     */
    public function getProducts ()
    {
        return $this->products->toArray();
    }

    /**
     * @return ClientECommerceIntegration[]
     */
    public function getECommerceIntegrations()
    {
        return $this->eCommerceIntegrations->toArray();
    }

    /**
     * @param ClientECommerceIntegration $clientECommerceIntegration
     */
    public function addECommerceIntegration(ClientECommerceIntegration $clientECommerceIntegration)
    {
        $clientECommerceIntegration->setClient($this);
        $this->eCommerceIntegrations->add($clientECommerceIntegration);
    }

    /**
     * @return ClientShippingIntegration[]
     */
    public function getShippingIntegrations()
    {
        return $this->shippingIntegrations->toArray();
    }

    /**
     * @param ClientShippingIntegration $clientShippingIntegration
     */
    public function addClientShippingIntegration (ClientShippingIntegration $clientShippingIntegration)
    {
        $clientShippingIntegration->setClient($this);
        $this->shippingIntegrations->add($clientShippingIntegration);
    }

}