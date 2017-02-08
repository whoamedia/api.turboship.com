<?php

namespace App\Models\ACL\Validation;


use App\Models\ACL\Permission;
use App\Repositories\Doctrine\ACL\PermissionRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PermissionValidation
{

    /**
     * @var PermissionRepository
     */
    private $permissionRepo;


    public function __construct()
    {
        $this->permissionRepo               = EntityManager::getRepository('App\Models\ACL\Permission');
    }


    /**
     * @param   int     $id
     * @return  Permission
     */
    public function idExists ($id)
    {
        $permission                         = $this->permissionRepo->getOneById($id);
        if (is_null($permission))
            throw new NotFoundHttpException('Permission not found');

        return $permission;
    }

}