<?php

namespace App\Models\Shipments;


use App\Models\CMS\Client;
use App\Models\CMS\Organization;
use App\Models\Locations\Address;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Shipper implements \JsonSerializable
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
     * @var Address
     */
    protected $address;

    /**
     * @var Address
     */
    protected $returnAddress;

    /**
     * Establishes which clients can use the shipper
     * @var ArrayCollection
     */
    protected $clients;


    public function __construct($data = [])
    {
        $this->clients                  = new ArrayCollection();

        $this->name                     = AU::get($data['name']);
        $this->organization             = AU::get($data['organization']);
        $this->address                  = AU::get($data['address']);
        $this->returnAddress            = AU::get($data['returnAddress']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['address']              = $this->address->jsonSerialize();
        $object['returnAddress']        = $this->returnAddress->jsonSerialize();

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
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return Address
     */
    public function getReturnAddress()
    {
        return $this->returnAddress;
    }

    /**
     * @param Address $returnAddress
     */
    public function setReturnAddress($returnAddress)
    {
        $this->returnAddress = $returnAddress;
    }

    /**
     * @return Client[]
     */
    public function getClients ()
    {
        return $this->clients->toArray();
    }

    /**
     * @param Client $client
     */
    public function addClient (Client $client)
    {
        $this->clients->add($client);
    }

    /**
     * @param Client $client
     * @return bool
     */
    public function hasClient (Client $client)
    {
        return $this->clients->contains($client);
    }

}