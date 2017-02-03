<?php

namespace App\Models\WMS;


use App\Models\CMS\Organization;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Printer implements \JsonSerializable
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
    protected $ipAddress;

    /**
     * @var PrinterType
     */
    protected $printerType;

    /**
     * @var Organization
     */
    protected $organization;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
        $this->ipAddress                = AU::get($data['ipAddress']);
        $this->printerType              = AU::get($data['printerType']);
        $this->organization             = AU::get($data['organization']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        $object['description']          = $this->description;
        $object['ipAddress']            = $this->ipAddress;
        $object['printerType']          = $this->printerType->jsonSerialize();
        $object['organization']         = $this->organization->jsonSerialize();

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
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return PrinterType
     */
    public function getPrinterType()
    {
        return $this->printerType;
    }

    /**
     * @param PrinterType $printerType
     */
    public function setPrinterType($printerType)
    {
        $this->printerType = $printerType;
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

}