<?php

namespace App\Integrations\EasyPost\Api;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostParcel;
use App\Integrations\EasyPost\Models\Responses\EasyPostParcel;


class ParcelApi extends BaseApi
{

    /**
     * @var string
     */
    protected $path = '/parcels';


    /**
     * @see https://www.easypost.com/docs/api.html#parcels
     * @param   CreateEasyPostParcel|array     $request
     * @return  EasyPostParcel
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateEasyPostParcel ? $request : new CreateEasyPostParcel($request);
        $response                       = parent::makeHttpRequest('post', $this->path, $request->jsonSerialize());

        return new EasyPostParcel($response);
    }

    /**
     * @param   string  $id
     * @return  EasyPostParcel
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', $this->path . '/' . $id);
        return new EasyPostParcel($response);
    }

}