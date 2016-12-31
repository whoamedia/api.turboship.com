<?php

namespace App\Http\Controllers;


use App\Http\Requests\Services\GetServices;
use App\Http\Requests\Services\ShowService;
use App\Models\Shipments\Service;
use App\Models\Shipments\Validation\ServiceValidation;
use App\Repositories\Doctrine\Shipments\ServiceRepository;
use Illuminate\Http\Request;
use EntityManager;

class ServiceController extends BaseAuthController
{


    /**
     * @var ServiceRepository
     */
    private $serviceRepo;


    public function __construct ()
    {
        $this->serviceRepo              = EntityManager::getRepository('App\Models\Shipments\Service');
    }

    public function index (Request $request)
    {
        $getServices                    = new GetServices($request->input());
        $getServices->validate();
        $getServices->clean();

        $query                          = $getServices->jsonSerialize();
        $results                        = $this->serviceRepo->where($query, false);
        return response($results);
    }


    public function show (Request $request)
    {
        $service                        = $this->getServiceFromRoute($request->route('id'));
        return response($service);
    }


    /**
     * @param   int     $id
     * @return  Service
     */
    private function getServiceFromRoute ($id)
    {
        $showService                    = new ShowService();
        $showService->setId($id);
        $showService->validate();
        $showService->clean();

        $serviceValidation              = new ServiceValidation();
        return $serviceValidation->idExists($showService->getId());
    }
}