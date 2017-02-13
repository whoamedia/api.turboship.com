<?php

namespace App\Http\Requests\ShippingStations;


use App\Http\Requests\BaseGet;
use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetShippingStations extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $names;

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $printerIds;


    public function __construct($data = [])
    {
        parent::__construct('shippingStation.id', $data);

        $this->names                    = AU::get($data['names']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->printerIds               = AU::get($data['printerIds']);
    }

    public function validate()
    {
        parent::validate();

        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->printerIds               = parent::validateIds($this->printerIds, 'printerIds');
    }

    public function clean ()
    {

    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['names']                = $this->names;
        $object['organizationIds']      = $this->organizationIds;
        $object['printerIds']           = $this->printerIds;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param null|string $names
     */
    public function setNames($names)
    {
        $this->names = $names;
    }

    /**
     * @return null|string
     */
    public function getOrganizationIds()
    {
        return $this->organizationIds;
    }

    /**
     * @param null|string $organizationIds
     */
    public function setOrganizationIds($organizationIds)
    {
        $this->organizationIds = $organizationIds;
    }

    /**
     * @return null|string
     */
    public function getPrinterIds()
    {
        return $this->printerIds;
    }

    /**
     * @param null|string $printerIds
     */
    public function setPrinterIds($printerIds)
    {
        $this->printerIds = $printerIds;
    }

}