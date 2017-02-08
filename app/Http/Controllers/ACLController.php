<?php

namespace App\Http\Controllers;


use App\Http\Requests\ACL\GetPermissions;
use App\Http\Requests\ACL\GetRoles;
use App\Http\Requests\ACL\ShowPermission;
use App\Http\Requests\ACL\ShowRole;
use App\Models\ACL\Permission;
use App\Models\ACL\Role;
use App\Repositories\Doctrine\ACL\PermissionRepository;
use App\Repositories\Doctrine\ACL\RoleRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ACLController extends BaseAuthController
{

    /**
     * @var PermissionRepository
     */
    private $permissionRepo;

    /**
     * @var RoleRepository
     */
    private $roleRepo;


    public function __construct ()
    {
        $this->permissionRepo           = EntityManager::getRepository('App\Models\ACL\Permission');
        $this->roleRepo                 = EntityManager::getRepository('App\Models\ACL\Role');
    }


    public function getPermissions (Request $request)
    {
        $getPermissions                 = new GetPermissions($request->input());
        $query                          = $getPermissions->jsonSerialize();

        $results                        = $this->permissionRepo->where($query, false);
        return response($results);
    }


    public function showPermission (Request $request)
    {
        $permission                     = $this->getPermissionFromRoute($request->route('id'));
        return response($permission);
    }


    public function getRoles (Request $request)
    {
        $getRoles                       = new GetRoles($request->input());
        $getRoles->validate();
        $getRoles->clean();
        $query                          = $getRoles->jsonSerialize();

        $results                        = $this->roleRepo->where($query, false);
        return response($results);
    }

    public function showRole (Request $request)
    {
        $role                           = $this->getRoleFromRoute($request->route('id'));
        return response($role);
    }

    /**
     * @param   int     $id
     * @return  Permission
     */
    private function getPermissionFromRoute ($id)
    {
        $showPermission                 = new ShowPermission();
        $showPermission->setId($id);
        $showPermission->validate();
        $showPermission->clean();

        $permission                     = $this->permissionRepo->getOneById($showPermission->getId());
        if (is_null($permission))
            throw new NotFoundHttpException('Permission not found');

        return $permission;
    }

    /**
     * @param   int     $id
     * @return  Role
     */
    private function getRoleFromRoute ($id)
    {
        $showRole                       = new ShowRole();
        $showRole->setId($id);
        $showRole->validate();
        $showRole->clean();

        $role                           = $this->roleRepo->getOneById($showRole->getId());
        if (is_null($role))
            throw new NotFoundHttpException('Role not found');

        return $role;
    }
}