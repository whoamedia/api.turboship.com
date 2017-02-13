<?php

namespace App\Models\Hardware;


use jamesvweston\Utilities\ArrayUtil AS AU;

use App\Models\CMS\Organization;

class ShippingStation implements \JsonSerializable
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
     * @var Printer
     */
    protected $printer;


    public function __construct(array $data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->organization             = AU::get($data['organization']);
        $this->printer                  = AU::get($data['printer']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['organization']         = $this->organization->jsonSerialize();
        $object['printer']              = $this->printer->jsonSerialize();

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
     * @return Printer
     */
    public function getPrinter()
    {
        return $this->printer;
    }

    /**
     * @param Printer $printer
     */
    public function setPrinter($printer)
    {
        $this->printer = $printer;
    }

}