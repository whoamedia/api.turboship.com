<?php

namespace App\Models\CMS;


use App\Models\BaseModel;
use App\Models\Integrations\ClientCredential;
use App\Models\WMS\Product;
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
    protected $credentials;

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
        $this->credentials              = new ArrayCollection();
        $this->products                 = new ArrayCollection();

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
        $object['createdAt']            = $this->createdAt;

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
     * @param ClientCredential $clientCredential
     */
    public function addCredential (ClientCredential $clientCredential)
    {
        $clientCredential->setClient($this);
        $this->credentials->add($clientCredential);
    }

    /**
     * @return ClientCredential[]
     */
    public function getCredentials ()
    {
        return $this->credentials->toArray();
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

}