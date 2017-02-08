<?php

namespace App\Models\ACL\Traits;


use App\Models\ACL\Role;
use Doctrine\Common\Collections\ArrayCollection;

trait HasRoles
{

    /**
     * @var ArrayCollection
     */
    protected $roles;


    /**
     * @return Role[]
     */
    public function getRoles ()
    {
        return $this->roles->toArray();
    }

    /**
     * @param Role $role
     */
    public function addRole (Role $role)
    {
        $this->roles->add($role);
    }

    /**
     * @param Role $role
     */
    public function removeRole (Role $role)
    {
        $this->roles->removeElement($role);
    }

}