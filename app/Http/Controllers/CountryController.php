<?php

namespace App\Http\Controllers;


use App\Http\Requests\Countries\GetCountriesRequest;
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
     * CountryController constructor.
     */
    public function __construct ()
    {
        $this->countryRepo              = EntityManager::getRepository('App\Models\Locations\Country');
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
        $country                        = $this->getCountryFromRoute($request->route('id'));
        return response($country);
    }

    /**
     * @param   Request         $request
     * @return  Country[]
     */
    public function getCountrySubdivisions (Request $request)
    {
        $country                        = $this->getCountryFromRoute($request->route('id'));
        return response()->json($country->getSubdivisions());
    }

    /**
     * @param   int     $id
     * @return  Country
     */
    private function getCountryFromRoute ($id)
    {
        $showCountryRequest             = new ShowCountryRequest();
        $showCountryRequest->setId($id);
        $showCountryRequest->validate();
        $showCountryRequest->clean();

        $countryValidation              = new CountryValidation($this->countryRepo);
        return $countryValidation->idExists($showCountryRequest->getId());
    }

}