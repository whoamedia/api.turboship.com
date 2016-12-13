<?php

namespace App\Http\Controllers;


use App\Requests\Users\GetUsersRequest;
use Illuminate\Http\Request;
use EntityManager;

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
        $authUser                       = \Auth::getUser();

        $getUsersRequest                = new GetUsersRequest($request->input());
        $getUsersRequest->setOrganizationIds($authUser->getOrganization()->getId());
        $getUsersRequest->validate();

        $query                          = $getUsersRequest->jsonSerialize();

        $results                        = $this->userRepo->where($query);
        return response($results);
    }


}