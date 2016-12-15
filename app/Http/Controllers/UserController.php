<?php

namespace App\Http\Controllers;


use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\ShowUserRequest;
use App\Http\Requests\Users\GetUsersRequest;
use App\Http\Requests\Users\UpdatePasswordRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\CMS\User;
use App\Models\CMS\Validation\UserValidation;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Hash;

class UserController extends Controller
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
    public function __construct ()
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
        $authUser                       = \Auth::getUser();

        $getUsersRequest                = new GetUsersRequest($request->input());
        $getUsersRequest->setOrganizationIds($authUser->getOrganization()->getId());
        $getUsersRequest->validate();
        $getUsersRequest->clean();

        $query                          = $getUsersRequest->jsonSerialize();

        $results                        = $this->userRepo->where($query, false);
        return response($results);
    }

    /**
     * @return  User
     */
    public function me ()
    {
        $authUser                       = \Auth::getUser();
        return response($authUser);
    }

    /**
     * @param   Request $request
     * @return  User
     */
    public function show (Request $request)
    {
        $showUserRequest                = new ShowUserRequest();
        $showUserRequest->setId($request->route('id'));
        $showUserRequest->validate();
        $showUserRequest->clean();

        $user                           = $this->userValidation->idExists($showUserRequest->getId(), true);

        return response($user);
    }

    /**
     * @param   Request $request
     * @return  User
     */
    public function update (Request $request)
    {
        $updateUserRequest              = new UpdateUserRequest($request->input());
        $updateUserRequest->setId($request->route('id'));
        $updateUserRequest->validate();
        $updateUserRequest->clean();

        $user                           = $this->userValidation->idExists($updateUserRequest->getId(), true);

        if ($user->getId() != \Auth::getUser()->getId())
            throw new AccessDeniedHttpException('Insufficient permissions to update other users');


        if (!is_null($updateUserRequest->getFirstName()))
            $user->setFirstName($updateUserRequest->getFirstName());

        if (!is_null($updateUserRequest->getLastName()))
            $user->setLastName($updateUserRequest->getLastName());

        if (!is_null($updateUserRequest->getEmail()))
        {
            if ($user->getEmail() != $updateUserRequest->getEmail())
            {
                $this->userValidation->uniqueEmail($updateUserRequest->getEmail());
                $user->setEmail($updateUserRequest->getEmail());
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
        $createUserRequest              = new CreateUserRequest($request->input());
        $createUserRequest->validate();

        $user                           = new User($createUserRequest->jsonSerialize());
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
        $updatePasswordRequest          = new UpdatePasswordRequest($request->input());
        $updatePasswordRequest->setId($request->route('id'));
        $updatePasswordRequest->validate();

        $user                           = $this->userValidation->idExists($updatePasswordRequest->getId(), true);

        if ($user->getId() != \Auth::getUser()->getId())
            throw new AccessDeniedHttpException('Insufficient permissions to update passwords for other users');

        if (!Hash::check($updatePasswordRequest->getCurrentPassword(), $user->getPassword()))
            throw new BadRequestHttpException('Password does not match');

        $user->setPassword($updatePasswordRequest->getNewPassword());

        $this->userRepo->saveAndCommit($user);
        return response($user);
    }

}