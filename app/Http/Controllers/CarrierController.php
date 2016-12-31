<?php

namespace App\Http\Controllers;


use App\Http\Requests\Carriers\GetCarriers;
use App\Http\Requests\Carriers\ShowCarrier;
use App\Models\Shipments\Carrier;
use App\Repositories\Doctrine\Shipments\CarrierRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarrierController extends BaseAuthController
{

    /**
     * @var CarrierRepository
     */
    private $carrierRepo;


    public function __construct ()
    {
        $this->carrierRepo              = EntityManager::getRepository('App\Models\Shipments\Carrier');
    }

    public function index (Request $request)
    {
        $getCarriers                    = new GetCarriers($request->input());
        $query                          = $getCarriers->jsonSerialize();

        $results                        = $this->carrierRepo->where($query, false);
        return response($results);
    }

    public function show (Request $request)
    {
        $carrier                        = $this->getCarrierFromRoute($request->route('id'));
        return response($carrier);
    }

    public function getServices (Request $request)
    {
        $carrier                        = $this->getCarrierFromRoute($request->route('id'));
        return response($carrier->getServices());
    }


    /**
     * @param   int     $id
     * @return  Carrier
     */
    private function getCarrierFromRoute ($id)
    {
        $showCarrier                    = new ShowCarrier();
        $showCarrier->setId($id);
        $showCarrier->validate();
        $showCarrier->clean();

        $carrier                        = $this->carrierRepo->getOneById($showCarrier->getId());

        if (is_null($carrier))
            throw new NotFoundHttpException('Carrier not found');

        return $carrier;
    }
}