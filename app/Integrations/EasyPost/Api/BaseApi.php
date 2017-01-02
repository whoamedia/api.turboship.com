<?php

namespace App\Integrations\EasyPost\Api;


use App\Integrations\EasyPost\EasyPostConfiguration;
use App\Integrations\EasyPost\Exceptions\EasyPostApiException;
use App\Integrations\EasyPost\Exceptions\EasyPostCustomsInfoException;
use App\Integrations\EasyPost\Exceptions\EasyPostInvalidAddressException;
use App\Integrations\EasyPost\Exceptions\EasyPostInvalidCredentialsException;
use App\Integrations\EasyPost\Exceptions\EasyPostPhoneNumberRequiredException;
use App\Integrations\EasyPost\Exceptions\EasyPostReferenceRequiredException;
use App\Integrations\EasyPost\Exceptions\EasyPostServiceUnavailableException;
use App\Integrations\EasyPost\Exceptions\EasyPostUnableToVoidShippedOrderException;
use App\Integrations\EasyPost\Exceptions\EasyPostUserThrottledException;
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
     *
     * @throws  EasyPostInvalidCredentialsException
     * @throws  EasyPostServiceUnavailableException
     * @throws  EasyPostPhoneNumberRequiredException
     * @throws  EasyPostCustomsInfoException
     * @throws  EasyPostReferenceRequiredException
     * @throws  EasyPostInvalidAddressException
     * @throws  EasyPostUserThrottledException
     * @throws  EasyPostUnableToVoidShippedOrderException
     * @throws  EasyPostApiException
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
            $errorMessage           = $errorMessage['error'];
            $message                = $errorMessage['message'];
            if ($code == 401)
                throw new EasyPostInvalidCredentialsException();
            else if (preg_match("/Unable to complete shipment purchase: carrier is not responding/", $message))
            {
                throw new EasyPostServiceUnavailableException();
            }
            else if (
                preg_match("/phoneNumber is required/", $message) ||
                preg_match("/Missing or invalid ship to phone number/", $message) ||
                preg_match("/RequestedShipment Recipient contact - phoneNumber is required/", $message))
                throw new EasyPostPhoneNumberRequiredException();
            else if (preg_match("/'customs_info' is required for international shipments, shipments bound for US military bases, or US territories/", $message))
                throw new EasyPostCustomsInfoException();
            else if (preg_match("/Missing required shipment attribute: reference/", $message))
            {
                throw new EasyPostReferenceRequiredException();
            }
            else if (preg_match("/Address is too ambiguous/", $message))
                throw new EasyPostInvalidAddressException();
            else if (preg_match("/The maximum number of user access attempts was exceeded/", $message) ||
                    preg_match("/The UserId is currently locked out/", $message))
                throw new EasyPostUserThrottledException($message, $code);
            else if (preg_match("/Unable to request refund. The parcel has been shipped/", $message))
                throw new EasyPostUnableToVoidShippedOrderException($message);
            else
                throw new EasyPostApiException($errorMessage['message'], $code);
        }

        $result                 = $response->getBody()->getContents();
        $result                 = json_decode($result, true);

        return $result;

    }
}