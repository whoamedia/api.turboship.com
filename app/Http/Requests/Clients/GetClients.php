<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetClients implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $names;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->names                    = AU::get($data['names']);
    }

    public function validate()
    {

    }

    public function clean ()
    {
        $this->ids                      = InputUtil::getIdsString($this->ids);
        $this->organizationIds          = InputUtil::getIdsString($this->organizationIds);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['organizationIds']      = $this->organizationIds;
        $object['names']                = $this->names;

        return $object;
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


}