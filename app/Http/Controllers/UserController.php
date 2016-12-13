<?php

namespace App\Http\Controllers;


use App\Requests\Users\GetUsersRequest;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{

    /**
     * @var \App\Repositories\Doctrine\CMS\UserRepository
     */
    private $userRepo;

    /**
     * UserController constructor.
     */
    public function __construct ()
    {
        $this->userRepo                 = EntityManager::getRepository('App\Models\CMS\User');
    }


    /**
     * @param   Request $request
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index (Request $request)
    {
        throw new \Exception('Testing bugsnag', 500);
        $authUser                       = \Auth::getUser();

        $getUsersRequest                = new GetUsersRequest($request->input());
        $getUsersRequest->setOrganizationIds($authUser->getOrganization()->getId());
        $getUsersRequest->validate();

        $query                          = $getUsersRequest->jsonSerialize();

        $results                        = $this->userRepo->where($query, false);
        return response($results);
    }

    public function me (Request $request)
    {
        $authUser                       = \Auth::getUser();

        return response($authUser);
    }

    public function show (Request $request)
    {
        $authUser                       = \Auth::getUser();

        $id                             = $request->route('id');
        $query['ids']                   = $id;
        $query['organizationIds']       = $authUser->getOrganization()->getId();

        $results                        = $this->userRepo->where($query, true);
        if (sizeof($results) != 1)
            throw new NotFoundHttpException('User not found');

        return response($results[0]);

    }

    public function update (Request $request)
    {

    }

    public function store (Request $request)
    {

    }

    public function updatePassword (Request $request)
    {

    }

}