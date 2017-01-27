<?php

namespace App\Http\Controllers;


use App\Http\Requests\IntegratedShippingApis\GetIntegratedShippingApis;
use App\Repositories\Doctrine\Integrations\IntegratedShippingApiRepository;
use EntityManager;
use Illuminate\Http\Request;

class IntegratedShippingApiController extends BaseIntegratedServiceController
{

    /**
     * @var IntegratedShippingApiRepository
     */
    protected $integratedShippingApiRepo;


    public function __construct()
    {
        parent::__construct('IntegratedShippingApi');
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

}