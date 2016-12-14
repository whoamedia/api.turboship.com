<?php

namespace App\Http\Controllers;


use App\Http\Requests\Countries\GetCountriesRequest;
use App\Http\Requests\Countries\GetCountrySubdivisionsRequest;
use App\Http\Requests\Countries\ShowCountryRequest;
use App\Models\Locations\Country;
use App\Models\Locations\Validation\CountryValidation;
use Illuminate\Http\Request;
use EntityManager;

class CountryController extends Controller
{

    /**
     * @var \App\Repositories\Doctrine\Locations\CountryRepository
     */
    private $countryRepo;

    /**
     * @var CountryValidation
     */
    private $countryValidation;

    /**
     * CountryController constructor.
     */
    public function __construct ()
    {
        $this->countryRepo              = EntityManager::getRepository('App\Models\Locations\Country');
        $this->countryValidation        = new CountryValidation($this->countryRepo);
    }

    /**
     * @param   Request $request
     * @return  \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index (Request $request)
    {
        $getCountriesRequest            = new GetCountriesRequest($request->input());
        $getCountriesRequest->validate();
        $getCountriesRequest->clean();

        $query                          = $getCountriesRequest->jsonSerialize();

        $results                        = $this->countryRepo->where($query, false);
        return response($results);
    }

    /**
     * @param   Request $request
     * @return  Country
     */
    public function show (Request $request)
    {
        $showCountryRequest             = new ShowCountryRequest();
        $showCountryRequest->setId($request->route('id'));
        $showCountryRequest->validate();
        $showCountryRequest->clean();

        $country                        = $this->countryValidation->idExists($showCountryRequest->getId());
        return response($country);
    }

    /**
     * @param   Request         $request
     * @return  Country[]
     */
    public function getCountrySubdivisions (Request $request)
    {
        $getCountrySubdivisionRequest   = new GetCountrySubdivisionsRequest();
        $getCountrySubdivisionRequest->setId($request->route('id'));
        $getCountrySubdivisionRequest->validate();
        $getCountrySubdivisionRequest->clean();

        $country                        = $this->countryValidation->idExists($getCountrySubdivisionRequest->getId());
        return response()->json($country->getSubdivisions());
    }

}