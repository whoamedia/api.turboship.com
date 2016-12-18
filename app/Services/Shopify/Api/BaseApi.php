<?php

namespace App\Services\Shopify\Api;


use App\Services\Shopify\Exceptions\InvalidShopifyCredentialsException;
use App\Services\Shopify\Exceptions\ShopifyApiException;
use App\Services\Shopify\ShopifyConfiguration;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class BaseApi
{

    /**
     * @var ShopifyConfiguration
     */
    protected $config;

    /**
     * @var Client
     */
    protected $guzzle;


    /**
     * BaseApi constructor.
     * @param ShopifyConfiguration $config
     */
    public function __construct(ShopifyConfiguration $config)
    {
        $this->config                   = $config;
        $this->guzzle                   = new Client();
    }

    /**
     * @param   string      $method
     * @param   string      $path
     * @param   array|null  $apiRequest
     * @return  array
     * @throws  InvalidShopifyCredentialsException
     * @throws  ShopifyApiException
     */
    protected function makeHttpRequest($method, $path, $apiRequest = [])
    {
        $url                        = $this->config->getUrl() . $path;
        $request                    = $apiRequest instanceof \JsonSerializable ? $apiRequest->jsonSerialize() : $apiRequest;

        try
        {
            switch ($method)
            {
                case 'post':
                    $response       = $this->guzzle->post($url, $request);
                    break;
                case 'put':
                    $response       = $this->guzzle->put($url, $request);
                    break;
                case 'delete':
                    $response       = $this->guzzle->delete($url, $request);
                    break;
                case 'get':
                    $response       = $this->guzzle->get($url, $request);
                    break;
                default:
                    return null;
            }
        }
        catch (ClientException $ex)
        {
            $errorMessage           = json_decode($ex->getResponse()->getBody()->getContents(), true);

            if ($ex->getCode() == 403)
                throw new InvalidShopifyCredentialsException();
            else
                throw new ShopifyApiException($errorMessage, $ex->getCode());
        }

        $result                     = json_decode($response->getBody(), true);

        return $result;
    }
}