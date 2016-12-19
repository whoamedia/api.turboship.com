<?php

namespace App\Models\OMS;


use App\Models\BaseModel;
use App\Models\CMS\Client;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var Client
     */
    protected $client;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var ArrayCollection
     */
    protected $aliases;

    /**
     * Product constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->aliases                  = new ArrayCollection();

        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
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
     * @return ProductAlias[]
     */
    public function getAliases ()
    {
        return $this->aliases->toArray();
    }

    /**
     * @param ProductAlias $alias
     */
    public function addAlias (ProductAlias $alias)
    {
        $alias->setProduct($this);
        $alias->setClient($this->client);
        $this->aliases->add($alias);
    }

}