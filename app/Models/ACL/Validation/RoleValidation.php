<?php

namespace App\Models\ACL\Validation;


use App\Models\ACL\Role;
use App\Repositories\Doctrine\ACL\RoleRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleValidation
{

    /**
     * @var RoleRepository
     */
    private $roleRepo;


    public function __construct()
    {
        $this->roleRepo                     = EntityManager::getRepository('App\Models\ACL\Role');
    }


    /**
     * @param   int     $id
     * @return  Role
     */
    public function idExists ($id)
    {
        $role                         = $this->roleRepo->getOneById($id);
        if (is_null($role))
            throw new NotFoundHttpException('Role not found');

        return $role;
    }

}