<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShippingApis\GetIntegratedShippingApis;
use App\Http\Requests\IntegratedShippingApis\ShowIntegratedShippingApi;
use App\Models\Integrations\IntegratedShippingApi;
use App\Models\Integrations\Validation\IntegratedShippingApiValidation;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use EntityManager;
use Illuminate\Http\Request;

class IntegratedShippingApiController extends BaseAuthController
{

    /**
     * @var IntegratedShippingApiRepository
     */
    protected $integratedShippingApiRepo;


    public function __construct()
    {
        $this->integratedShippingApiRepo    = EntityManager::getRepository('App\Models\Integrations\IntegratedShippingApi');
    }


    public function index (Request $request)
    {
        $getIntegratedShoppingCarts         = new GetIntegratedShippingApis($request->input());
        $getIntegratedShoppingCarts->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getIntegratedShoppingCarts->validate();
        $getIntegratedShoppingCarts->clean();

        $query                              = $getIntegratedShoppingCarts->jsonSerialize();

        $results                            = $this->integratedShippingApiRepo->where($query, false);
        return response($results);
    }


    public function show (Request $request)
    {
        $showIntegratedShippingApi          = new ShowIntegratedShippingApi();
        $showIntegratedShippingApi->setId($request->route('id'));

        $integratedShippingApi              = $this->getIntegratedShippingApiFromRoute($showIntegratedShippingApi->getId());
        return response($integratedShippingApi);
    }

    /**
     * @param   int     $id
     * @return  IntegratedShippingApi
     */
    public function getIntegratedShippingApiFromRoute ($id)
    {
        $integratedShippingApiValidation    = new IntegratedShippingApiValidation();
        $integratedShippingApi              = $integratedShippingApiValidation->idExists($id);
        return $integratedShippingApi;
    }
}