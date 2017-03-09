<?php

namespace App\Http\Requests\Users;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseGet;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetUsers extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

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
        parent::__construct('user.id', $data);

        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->firstNames               = AU::get($data['firstNames']);
        $this->lastNames                = AU::get($data['lastNames']);
        $this->emails                   = AU::get($data['emails']);
    }

    public function validate()
    {
        parent::validate();

        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
    }

    public function clean ()
    {
        parent::clean();
        $this->organizationIds          = InputUtil::getIdsString($this->organizationIds);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['organizationIds']      = $this->organizationIds;
        $object['firstNames']           = $this->firstNames;
        $object['lastNames']            = $this->lastNames;
        $object['emails']               = $this->emails;

        return $object;
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