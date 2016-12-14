<?php

namespace App\Http\Requests\Users;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetUsersRequest implements Cleanable, Validatable, \JsonSerializable
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
    protected $firstNames;

    /**
     * @var string|null
     */
    protected $lastNames;

    /**
     * @var string|null
     */
    protected $emails;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->firstNames               = AU::get($data['firstNames']);
        $this->lastNames                = AU::get($data['lastNames']);
        $this->emails                   = AU::get($data['emails']);
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
        $object['firstNames']           = $this->firstNames;
        $object['lastNames']            = $this->lastNames;
        $object['emails']               = $this->emails;

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
    public function getFirstNames()
    {
        return $this->firstNames;
    }

    /**
     * @param null|string $firstNames
     */
    public function setFirstNames($firstNames)
    {
        $this->firstNames = $firstNames;
    }

    /**
     * @return null|string
     */
    public function getLastNames()
    {
        return $this->lastNames;
    }

    /**
     * @param null|string $lastNames
     */
    public function setLastNames($lastNames)
    {
        $this->lastNames = $lastNames;
    }

    /**
     * @return null|string
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param null|string $emails
     */
    public function setEmails($emails)
    {
        $this->emails = $emails;
    }

}