<?php

namespace App\Integrations\EasyPost\Api;


class ApiKeyApi extends BaseApi
{

    /**
     * @var string
     */
    protected $path = '/api_keys';


    /**
     * PRODUCTION ONLY
     * @see https://www.easypost.com/docs/api.html#retrieve-a-api-key
     */
    public function show ()
    {

    }



}