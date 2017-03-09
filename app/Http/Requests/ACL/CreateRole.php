<?php

namespace App\Http\Requests\ACL;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateRole extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $permissionIds;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
        $this->permissionIds            = AU::get($data['permissionIds']);
    }

    public function validate()
    {
        if (is_null($this->name) || empty(trim($this->name)))
            throw new BadRequestHttpException('name is required');

        if (is_null($this->description) || empty(trim($this->description)))
            throw new BadRequestHttpException('description is required');

        if (is_null($this->permissionIds) || empty(trim($this->permissionIds)))
            throw new BadRequestHttpException('permissionIds is required');

        $this->permissionIds            = parent::validateIds($this->permissionIds, 'permissionIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['name']                 = $this->name;
        $object['description']          = $this->description;
        $object['permissionIds']        = $this->permissionIds;

        return $object;
    }

    public function clean ()
    {

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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPermissionIds()
    {
        return $this->permissionIds;
    }

    /**
     * @param string $permissionIds
     */
    public function setPermissionIds($permissionIds)
    {
        $this->permissionIds = $permissionIds;
    }

}