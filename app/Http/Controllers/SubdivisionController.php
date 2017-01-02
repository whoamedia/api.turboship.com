<?php

namespace App\Http\Controllers;


use App\Http\Requests\Subdivisions\GetSubdivisions;
use App\Http\Requests\Subdivisions\ShowSubdivision;
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
        $getSubdivisions                = new GetSubdivisions($request->input());
        $getSubdivisions->validate();
        $getSubdivisions->clean();

        $query                          = $getSubdivisions->jsonSerialize();

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
        $showSubdivision                    = new ShowSubdivision();
        $showSubdivision->setId($id);
        $showSubdivision->validate();
        $showSubdivision->clean();

        $subdivisionValidation              = new SubdivisionValidation();
        return $subdivisionValidation->idExists($showSubdivision->getId());
    }
}