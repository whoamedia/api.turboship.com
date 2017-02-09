<?php

namespace App\Http\Controllers;


use App\Http\Requests\Users\CreateUser;
use App\Http\Requests\Users\ShowUser;
use App\Http\Requests\Users\GetUsers;
use App\Http\Requests\Users\UpdatePassword;
use App\Http\Requests\Users\UpdateUser;
use App\Models\ACL\Validation\PermissionValidation;
use App\Models\ACL\Validation\RoleValidation;
use App\Models\CMS\User;
use App\Models\CMS\Validation\ClientValidation;
use App\Models\CMS\Validation\UserValidation;
use App\Models\Support\Validation\SourceValidation;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Services\ImageService;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseAuthController
{

    /**
     * @var ClientRepository
     */
    private $clientRepo;

    /**
     * @var \App\Repositories\Doctrine\CMS\UserRepository
     */
    private $userRepo;

    /**
     * @var UserValidation
     */
    private $userValidation;


    /**
     * UserController constructor.
     */
    public function __construct (Request $request)
    {
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
        $this->userRepo                 = EntityManager::getRepository('App\Models\CMS\User');
        $this->userValidation           = new UserValidation($this->userRepo);
    }


    /**
     * @param   Request $request
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index (Request $request)
    {
        $getUsers                       = new GetUsers($request->input());
        $getUsers->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getUsers->validate();
        $getUsers->clean();

        $query                          = $getUsers->jsonSerialize();

        $results                        = $this->userRepo->where($query, false);
        return response($results);
    }

    /**
     * @return  User
     */
    public function me ()
    {
        $authUser                       = parent::getAuthUser();
        return response($authUser);
    }

    /**
     * @param   Request $request
     * @return  User
     */
    public function show (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));
        return response($user);
    }

    /**
     * @param   Request $request
     * @return  User
     */
    public function update (Request $request)
    {
        $updateUser                     = new UpdateUser($request->input());
        $updateUser->setId($request->route('id'));
        $updateUser->validate();
        $updateUser->clean();

        $user                           = $this->getUserFromRoute($updateUser->getId());

        if ($user->getId() != parent::getAuthUser()->getId())
            throw new AccessDeniedHttpException('Insufficient permissions to update other users');


        if (!is_null($updateUser->getFirstName()))
            $user->setFirstName($updateUser->getFirstName());

        if (!is_null($updateUser->getLastName()))
            $user->setLastName($updateUser->getLastName());

        if (!is_null($updateUser->getEmail()))
        {
            if ($user->getEmail() != $updateUser->getEmail())
            {
                $this->userValidation->uniqueEmail($updateUser->getEmail());
                $user->setEmail($updateUser->getEmail());
            }
        }

        $this->userRepo->saveAndCommit($user);

        return response($user);
    }

    /**
     * @param   Request $request
     * @return  User
     */
    public function store (Request $request)
    {
        $createUser                     = new CreateUser($request->input());
        $createUser->validate();

        $user                           = new User($createUser->jsonSerialize());

        if (parent::authUserIsClient())
        {
            $user->setClient(parent::getAuthUser()->getClient());
        }
        else
        {
            if (!is_null($createUser->getClientId()))
            {
                $clientValidation       = new ClientValidation($this->clientRepo);
                $client                 = $clientValidation->idExists($createUser->getClientId());
                if (!parent::getAuthUser()->getOrganization()->hasClient($client))
                    throw new BadRequestHttpException('Client not found');

                $user->setClient($client);
            }
        }

        $user->setOrganization(parent::getAuthUserOrganization());

        $user->validate();

        //  TODO: Move this to User model
        $this->userValidation->uniqueEmail($user->getEmail());

        $this->userRepo->saveAndCommit($user);
        return response($user, 201);
    }

    /**
     * @param   Request $request
     * @return  User
     */
    public function updatePassword (Request $request)
    {
        $updatePassword                 = new UpdatePassword($request->input());
        $updatePassword->setId($request->route('id'));
        $updatePassword->validate();

        $user                           = $this->getUserFromRoute($updatePassword->getId());

        if ($user->getId() != parent::getAuthUser()->getId())
            throw new AccessDeniedHttpException('Insufficient permissions to update passwords for other users');

        if (!Hash::check($updatePassword->getCurrentPassword(), $user->getPassword()))
            throw new BadRequestHttpException('Password does not match');

        $user->setPassword($updatePassword->getNewPassword());

        $this->userRepo->saveAndCommit($user);
        return response($user);
    }

    public function updateImage (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));
        $file                           = $request->file('image');


        if (is_null($file))
            throw new BadRequestHttpException('image is required');

        if (!$file->isValid())
            throw new BadRequestHttpException('files is invalid');

        if (!preg_match('#^image#', $file->getMimeType()))
            throw new BadRequestHttpException('Invalid mime type');

        $sourceValidation               = new SourceValidation();
        $internalSource                 = $sourceValidation->getInternal();
        $imageService                   = new ImageService();

        $image                          = $imageService->handleImage($file->getPath() . '/' . $file->getBasename(), $file->getClientOriginalName());
        $image->setSource($internalSource);

        $user->setImage($image);
        $this->userRepo->saveAndCommit($user);
        return response($user->getImage(), 201);
    }

    public function getPermissions (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));
        return response($user->getPermissions());
    }

    public function createPermissions (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));

        $permissionIds                  = $request->input('permissionIds');
        if (is_null($permissionIds))
            throw new BadRequestHttpException('permissionIds is required');

        $permissionValidation           = new PermissionValidation();
        $permissionIds                  = str_replace(' ', '', $permissionIds);
        foreach (explode(',', $permissionIds) AS $id)
        {
            $permission                 = $permissionValidation->idExists($id);
            if ($user->hasPermission($permission))
                throw new BadRequestHttpException('User already has permission ' . $permission->getName());
            $user->addPermission($permission);
        }

        $this->userRepo->saveAndCommit($user);
        return response($user->getPermissions(), 201);
    }

    public function updatePermissions (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));

        $permissionIds                  = $request->input('permissionIds');
        if (is_null($permissionIds))
            throw new BadRequestHttpException('permissionIds is required');

        $user->emptyPermissions();
        $permissionValidation           = new PermissionValidation();
        $permissionIds                  = str_replace(' ', '', $permissionIds);
        foreach (explode(',', $permissionIds) AS $id)
        {
            $permission                 = $permissionValidation->idExists($id);
            $user->addPermission($permission);
        }

        $this->userRepo->saveAndCommit($user);
        return response($user->getPermissions(), 201);
    }

    public function deletePermission (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));

        $permissionId                   = $request->route('permissionId');
        if (is_null($permissionId))
            throw new BadRequestHttpException('permissionId is required');

        $permissionValidation           = new PermissionValidation();
        $permission                     = $permissionValidation->idExists($permissionId);

        if (!$user->hasPermission($permission))
            throw new BadRequestHttpException('User does not have permission ' . $permission->getName());

        $user->removePermission($permission);
        $this->userRepo->saveAndCommit($user);
        return response('', 204);
    }

    public function getRoles (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));
        return response($user->getRoles());
    }

    public function createRoles (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));
        $roleIds                        = $request->input('roleIds');
        if (is_null($roleIds))
            throw new BadRequestHttpException('roleIds is required');

        $roleValidation                 = new RoleValidation();
        foreach (explode(',', $roleIds) AS $roleId)
        {
            $role                       = $roleValidation->idExists($roleId);
            if ($user->hasRole($role))
                throw new BadRequestHttpException('User already has role ' . $role->getName());

            $user->addRole($role);
        }
        $this->userRepo->saveAndCommit($user);
        return response($user->getRoles(), 201);
    }

    public function updateRoles (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));
        $roleIds                        = $request->input('roleIds');
        if (is_null($roleIds))
            throw new BadRequestHttpException('roleIds is required');

        $user->emptyRoles();
        $roleValidation                 = new RoleValidation();
        foreach (explode(',', $roleIds) AS $roleId)
        {
            $role                       = $roleValidation->idExists($roleId);
            $user->addRole($role);
        }
        $this->userRepo->saveAndCommit($user);
        return response($user->getRoles(), 201);
    }

    public function deleteRole (Request $request)
    {
        $user                           = $this->getUserFromRoute($request->route('id'));
        $roleId                         = $request->route('roleId');
        if (is_null($roleId))
            throw new BadRequestHttpException('roleId is required');

        $roleValidation                 = new RoleValidation();
        $role                           = $roleValidation->idExists($roleId);

        if (!$user->hasRole($role))
            throw new NotFoundHttpException('User does not have role ' . $role->getName());

        $user->removeRole($role);
        $this->userRepo->saveAndCommit($user);
        return response('', 204);
    }
    /**
     * @param   int     $id
     * @return  User
     */
    private function getUserFromRoute ($id)
    {
        $showUser                       = new ShowUser();
        $showUser->setId($id);
        $showUser->validate();
        $showUser->clean();

        $user                           = $this->userValidation->idExists($showUser->getId());

        if ($this->getAuthUserOrganization()->getId() != $user->getOrganization()->getId())
            throw new NotFoundHttpException('User not found');

        return $user;
    }
}