<?php

namespace App\Integrations\EasyPost\Api;
use App\Integrations\EasyPost\EasyPostConfiguration;


/**
 * Class BaseApi
 * @package App\Integrations\EasyPost\Api
 */
class BaseApi
{

    /**
     * @var EasyPostConfiguration
     */
    protected $easyPostConfiguration;

    public function __construct(EasyPostConfiguration $easyPostConfiguration)
    {
        $this->easyPostConfiguration    = $easyPostConfiguration;
    }


    /**
     * code	    reason-phrase	        description
     * 200	    OK	                    The request was successful
     * 201	    Created	                The request was successful and one or more resources was created
     * 400	    Bad Request	            Request not processed due to client error
     * 401	    Unauthorized	        Authentication is required and has failed
     * 402	    Payment Required	    Lack of billing information or insufficient funds
     * 404	    Not Found	            The requested resource could not be found
     * 422	    Unprocessable Entity	The request was well-formed but unable to process the contained instructions
     */


    protected function makeHttpRequest($method, $path, $apiRequest = [])
    {
        $url                            = $this->easyPostConfiguration->getUrl() . $path;
    }
}