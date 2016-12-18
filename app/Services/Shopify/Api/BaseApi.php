<?php

namespace App\Services\Shopify\Api;


use App\Services\Shopify\Exceptions\InvalidShopifyCredentialsException;
use App\Services\Shopify\Exceptions\ShopifyApiException;
use App\Services\Shopify\Exceptions\ShopifyBadRequestException;
use App\Services\Shopify\Exceptions\ShopifyItemNotFoundException;
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
     * @throws  ShopifyBadRequestException
     * @throws  InvalidShopifyCredentialsException
     * @throws  ShopifyItemNotFoundException
     * @throws  ShopifyApiException
     */
    protected function makeHttpRequest($method, $path, $apiRequest = [])
    {
        $url                        = $this->config->getUrl() . $path;
        $request                    = $apiRequest instanceof \JsonSerializable ? $apiRequest->jsonSerialize() : $apiRequest;

        $data       = [
            'query'                 => $request,
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
            $errorMessage           = json_decode($ex->getResponse()->getBody()->getContents(), true);

            if (is_array($errorMessage))
            {
                $errorMessage       = isset($errorMessage['errors']) ? $errorMessage['errors'] : $errorMessage;
            }

            if ($ex->getCode() == 400)
                throw new ShopifyBadRequestException();
            else if ($ex->getCode() == 403)
                throw new InvalidShopifyCredentialsException();
            else if ($ex->getCode() == 404)
                throw new ShopifyItemNotFoundException('Item not found', $ex->getCode());
            else
                throw new ShopifyApiException($errorMessage, $ex->getCode());
        }

        $result                     = json_decode($response->getBody(), true);

        return $result;
    }
}