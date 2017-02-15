<?php

namespace App\Http\Requests\ACL;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseGet;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetPermissions extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $entities;

    /**
     * @var string|null
     */
    protected $names;


    public function __construct($data = [])
    {
        parent::__construct('permission.id', $data);

        $this->entities                 = AU::get($data['entities']);
        $this->names                    = AU::get($data['names']);
    }

    public function validate()
    {
        parent::validate();
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['entities']             = $this->entities;
        $object['names']                = $this->names;

        return $object;
    }

    public function clean ()
    {
        parent::clean();
    }

    /**
     * @return null|string
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param null|string $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
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