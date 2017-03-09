<?php

namespace App\Http\Controllers;


use App\Http\Requests\Postage\GetPostage;
use App\Http\Requests\Postage\ShowPostage;
use App\Models\Shipments\Postage;
use App\Models\Shipments\Validation\PostageValidation;
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

    public function getLexicon (Request $request)
    {
        $getPostage                     = new GetPostage($request->input());
        $getPostage->validate();
        $getPostage->clean();

        $query                          = $getPostage->jsonSerialize();
        $results                        = $this->postageRepo->getLexicon($query);

        return response($results);
    }

    public function show (Request $request)
    {
        $postage                        = $this->getPostageFromRoute($request->route('id'));
        return response($postage);
    }

    /**
     * @param   int     $id
     * @return  Postage
     */
    private function getPostageFromRoute ($id)
    {
        $showPostage                    = new ShowPostage();
        $showPostage->setId($id);
        $showPostage->validate();
        $showPostage->clean();

        $postageValidation              = new PostageValidation();
        $postage                        = $postageValidation->idExists($id);

        return $postage;
    }
}