<?php

namespace App\Models\ACL\Traits;


use App\Models\ACL\Permission;
use Doctrine\Common\Collections\ArrayCollection;

trait HasPermissions
{

    /**
     * @var ArrayCollection
     */
    protected $permissions;


    /**
     * @return Permission[]
     */
    public function getPermissions ()
    {
        return $this->permissions->toArray();
    }

    /**
     * @param Permission $permission
     */
    public function addPermission (Permission $permission)
    {
        $this->permissions->add($permission);
    }

    /**
     * @param Permission $permission
     */
    public function removePermission (Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    /**
     * @param   Permission $permission
     * @return  bool
     */
    public function hasPermission (Permission $permission)
    {
        return $this->permissions->contains($permission);
    }
}