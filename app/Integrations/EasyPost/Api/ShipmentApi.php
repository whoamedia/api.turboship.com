<?php

namespace App\Integrations\EasyPost\Api;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostShipment;
use App\Integrations\EasyPost\Models\Responses\EasyPostShipment;

class ShipmentApi extends BaseApi
{

    /**
     * @var string
     */
    protected $path = '/shipments';


    /**
     * @see https://www.easypost.com/docs/api.html#shipments
     * @param   CreateEasyPostShipment|array     $request
     * @return  EasyPostShipment
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateEasyPostShipment ? $request : new CreateEasyPostShipment($request);
        $response                       = parent::makeHttpRequest('post', $this->path, $request->jsonSerialize());

        return new EasyPostShipment($response);
    }

    /**
     * @param   string  $id
     * @return  EasyPostShipment
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', $this->path . '/' . $id);
        return new EasyPostShipment($response);
    }

}