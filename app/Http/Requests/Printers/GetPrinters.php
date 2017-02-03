<?php

namespace App\Http\Requests\Printers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetPrinters extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $names;

    /**
     * @var string|null
     */
    protected $printerTypeIds;

    /**
     * @var string|null
     */
    protected $organizationIds;

    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->names                    = AU::get($data['names']);
        $this->printerTypeIds           = AU::get($data['printerTypeIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->printerTypeIds           = parent::validateIds($this->printerTypeIds, 'printerTypeIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['names']                = $this->names;
        $object['printerTypeIds']       = $this->printerTypeIds;
        $object['organizationIds']      = $this->organizationIds;

        return $object;
    }

    public function clean ()
    {

    }

    /**
     * @return null|string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param null|string $ids
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
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
    public function getPrinterTypeIds()
    {
        return $this->printerTypeIds;
    }

    /**
     * @param null|string $printerTypeIds
     */
    public function setPrinterTypeIds($printerTypeIds)
    {
        $this->printerTypeIds = $printerTypeIds;
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

}