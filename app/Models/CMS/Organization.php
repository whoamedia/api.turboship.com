<?php

namespace App\Models\CMS;


use App\Models\BaseModel;
use App\Models\Shipments\Shipper;
use App\Models\Shipments\ShippingContainer;
use App\Models\WMS\Printer;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @SWG\Definition()
 */
class Organization extends BaseModel implements \JsonSerializable
{

    /**
     * @SWG\Property(example="1")
     * @var int
     */
    protected $id;

    /**
     * @SWG\Property(example="Joe's Fulfillment Company")
     * @var string
     */
    protected $name;

    /**
     * @var ArrayCollection
     */
    protected $clients;

    /**
     * @var ArrayCollection
     */
    protected $printers;

    /**
     * @var ArrayCollection
     */
    protected $users;

    /**
     * @var ArrayCollection
     */
    protected $shippingContainers;

    /**
     * @var ArrayCollection
     */
    protected $shippers;

    /**
     * @var \DateTime
     */
    protected $createdAt;
    
    
    /**
     * Organization constructor.
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        $this->createdAt                = new \DateTime();
        $this->clients                  = new ArrayCollection();
        $this->printers                 = new ArrayCollection();
        $this->users                    = new ArrayCollection();
        $this->shippingContainers       = new ArrayCollection();
        $this->shippers                 = new ArrayCollection();
        
        if (is_array($data))
        {
            $this->name                 = AU::get($data['name']);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        
        return $object;
    }
    
    public function validate()
    {

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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        $user->setOrganization($this);
        $this->users->add($user);
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users->toArray();
    }

    /**
     * @param Client $client
     */
    public function addClient(Client $client)
    {
        $client->setOrganization($this);
        $this->clients->add($client);
    }

    /**
     * @return Client[]
     */
    public function getClients ()
    {
        return $this->clients->toArray();
    }

    /**
     * @param   Client $client
     * @return  bool
     */
    public function hasClient (Client $client)
    {
        foreach ($this->getClients() AS $item)
        {
            if ($client->getId() == $item->getId())
                return true;
        }
        return false;
    }

    /**
     * @param Printer $printer
     */
    public function addPrinter (Printer $printer)
    {
        $printer->setOrganization($this);
        $this->printers->add($printer);
    }

    /**
     * @return Printer[]
     */
    public function getPrinters ()
    {
        return $this->printers->toArray();
    }

    /**
     * @return ShippingContainer[]
     */
    public function getShippingContainers ()
    {
        return $this->shippingContainers->toArray();
    }

    /**
     * @param ShippingContainer $shippingContainer
     */
    public function addShippingContainer (ShippingContainer $shippingContainer)
    {
        $shippingContainer->setOrganization($this);
        $this->shippingContainers->add($shippingContainer);
    }

    /**
     * @return Shipper[]
     */
    public function getShippers ()
    {
        return $this->shippers->toArray();
    }

    /**
     * @param Shipper $shipper
     */
    public function addShipper (Shipper $shipper)
    {
        $shipper->setOrganization($this);
        $this->shippers->add($shipper);
    }

}