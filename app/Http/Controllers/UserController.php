<?php

namespace App\Http\Controllers;


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
        $query                          = [];

        $authUser                       = \Auth::getUser();

        $query['organizationIds']       = $authUser->getOrganization()->getId();
        $query['ids']                   = $request->input('ids');
        $query['firstNames']            = $request->input('firstNames');
        $query['lastNames']             = $request->input('lastNames');
        $query['emails']                = $request->input('emails');

        $results                        = $this->userRepo->where($query);
        return response($results);
    }


}