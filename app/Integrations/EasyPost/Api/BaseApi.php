<?php

namespace App\Integrations\EasyPost\Api;


use App\Integrations\EasyPost\EasyPostConfiguration;
use App\Integrations\EasyPost\Exceptions\EasyPostInvalidCredentialsException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class BaseApi
 * @package App\Integrations\EasyPost\Api
 */
class BaseApi
{

    /**
     * @var EasyPostConfiguration
     */
    protected $config;

    /**
     * @var Client
     */
    protected $guzzle;


    public function __construct(EasyPostConfiguration $config)
    {
        $this->config                   = $config;
        $this->guzzle                   = new Client();
    }




    /**
     * @param   string      $method
     * @param   string      $path
     * @param   array|null  $apiRequest
     * @param   array|null  $queryString
     * @return  array
     * @throws  EasyPostInvalidCredentialsException
     */
    protected function makeHttpRequest($method, $path, $apiRequest = null, $queryString = null)
    {
        $url                            = $this->config->getUrl() . $path;

        $data       = [
            'headers'               => [
                'Authorization'     => 'Bearer ' . $this->config->getApiKey(),
            ],
            'json'                  => $apiRequest,
            'query'                 => $queryString,
        ];

        try
        {
            switch ($method)
            {
                case 'post':
                    $response       = $this->guzzle->post($url, $data);
                    break;
                case 'put':
                    $response       = $this->guzzle->put($url, $data);
                    break;
                case 'delete':
                    $response       = $this->guzzle->delete($url, $data);
                    break;
                case 'get':
                    $response       = $this->guzzle->get($url, $data);
                    break;
                default:
                    return null;
            }
        }
        catch (ClientException $ex)
        {
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
            $code                   = $ex->getCode();
            $errorMessage           = json_decode($ex->getResponse()->getBody()->getContents(), true);
            dd($errorMessage);
            if ($code == 401)
                throw new EasyPostInvalidCredentialsException();
        }

        $result                 = $response->getBody()->getContents();
        $result                 = json_decode($result, true);

        return $result;

    }
}