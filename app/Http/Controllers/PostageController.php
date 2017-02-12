<?php

namespace App\Http\Controllers;


use App\Http\Requests\Postage\GetPostage;
use App\Repositories\Doctrine\Shipments\PostageRepository;
use EntityManager;
use Illuminate\Http\Request;

class PostageController extends BaseAuthController
{


    /**
     * @var PostageRepository
     */
    private $postageRepo;


    public function __construct()
    {
        $this->postageRepo              = EntityManager::getRepository('App\Models\Shipments\Postage');
    }


    public function index (Request $request)
    {
        $getPostage                     = new GetPostage($request->input());
        $getPostage->validate();
        $getPostage->clean();

        $query                          = $getPostage->jsonSerialize();
        $results                        = $this->postageRepo->where($query, false);

        return response($results);
    }
}