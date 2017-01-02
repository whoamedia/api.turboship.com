<?php

namespace App\Http\Controllers;


use App\Http\Requests\Users\CreateUser;
use App\Http\Requests\Users\ShowUser;
use App\Http\Requests\Users\GetUsers;
use App\Http\Requests\Users\UpdatePassword;
use App\Http\Requests\Users\UpdateUser;
use App\Models\CMS\User;
use App\Models\CMS\Validation\UserValidation;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseAuthController
{

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
        $user->setOrganization(\Auth::getUser()->getOrganization());

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