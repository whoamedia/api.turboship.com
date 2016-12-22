<?php

namespace App\Integrations\EasyPost\Api;


use App\Integrations\EasyPost\Models\Requests\CreateEasyPostAddress;
use App\Integrations\EasyPost\Models\Responses\EasyPostAddress;

class AddressApi extends BaseApi
{

    /**
     * @var string
     */
    protected $path = '/addresses';


    /**
     * @see https://www.easypost.com/docs/api.html#create-and-verify-addresses
     * @param   CreateEasyPostAddress|array     $request
     * @return  EasyPostAddress
     */
    public function create ($request = [])
    {
        $request                        = $request instanceof CreateEasyPostAddress ? $request : new CreateEasyPostAddress($request);
        $response                       = parent::makeHttpRequest('post', $this->path, $request->jsonSerialize());

        return new EasyPostAddress($response);
    }

    /**
     * @param   string  $id
     * @return  EasyPostAddress
     */
    public function show ($id)
    {
        $response                       = parent::makeHttpRequest('get', $this->path . '/' . $id);
        return new EasyPostAddress($response);
    }

}