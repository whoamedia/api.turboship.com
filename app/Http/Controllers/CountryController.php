<?php

namespace App\Http\Controllers;


use App\Http\Requests\Countries\GetCountries;
use App\Http\Requests\Countries\ShowCountry;
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
        $getCountries                   = new GetCountries($request->input());
        $getCountries->validate();
        $getCountries->clean();

        $query                          = $getCountries->jsonSerialize();

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
        $showCountry                    = new ShowCountry();
        $showCountry->setId($id);
        $showCountry->validate();
        $showCountry->clean();

        $countryValidation              = new CountryValidation($this->countryRepo);
        return $countryValidation->idExists($showCountry->getId());
    }

}