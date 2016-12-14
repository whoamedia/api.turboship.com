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
     * @var SubdivisionValidation
     */
    private $subdivisionValidation;


    /**
     * SubdivisionController constructor.
     */
    public function __construct ()
    {
        $this->subdivisionRepo          = EntityManager::getRepository('App\Models\Locations\Subdivision');
        $this->subdivisionValidation    = new SubdivisionValidation($this->subdivisionRepo);
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
        $showSubdivisionRequest             = new ShowSubdivisionRequest();
        $showSubdivisionRequest->setId($request->route('id'));
        $showSubdivisionRequest->validate();
        $showSubdivisionRequest->clean();

        $subdivision                        = $this->subdivisionValidation->idExists($showSubdivisionRequest->getId());
        return response($subdivision);
    }

}