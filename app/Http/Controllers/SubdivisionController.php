<?php

namespace App\Http\Controllers;


use App\Http\Requests\Subdivisions\GetSubdivisionsRequest;
use App\Http\Requests\Subdivisions\ShowSubdivisionRequest;
use App\Models\Locations\Subdivision;
use App\Models\Locations\Validation\SubdivisionValidation;
use EntityManager;
use Illuminate\Http\Request;

class SubdivisionController extends Controller
{

    /**
     * @var \App\Repositories\Doctrine\Locations\SubdivisionRepository
     */
    private $subdivisionRepo;


    /**
     * SubdivisionController constructor.
     */
    public function __construct ()
    {
        $this->subdivisionRepo          = EntityManager::getRepository('App\Models\Locations\Subdivision');
    }


    /**
     * @param   Request $request
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index (Request $request)
    {
        $getSubdivisionsRequest         = new GetSubdivisionsRequest($request->input());
        $getSubdivisionsRequest->validate();
        $getSubdivisionsRequest->clean();

        $query                          = $getSubdivisionsRequest->jsonSerialize();

        $results                        = $this->subdivisionRepo->where($query, false);
        return response($results);
    }

    /**
     * @param   Request $request
     * @return  Subdivision
     */
    public function show (Request $request)
    {
        $subdivision                        = $this->getSubdivisionFromRoute($request->route('id'));
        return response($subdivision);
    }


    /**
     * @param   int     $id
     * @return  Subdivision
     */
    private function getSubdivisionFromRoute ($id)
    {
        $showSubdivisionRequest             = new ShowSubdivisionRequest();
        $showSubdivisionRequest->setId($id);
        $showSubdivisionRequest->validate();
        $showSubdivisionRequest->clean();

        $subdivisionValidation              = new SubdivisionValidation();
        return $subdivisionValidation->idExists($showSubdivisionRequest->getId());
    }
}