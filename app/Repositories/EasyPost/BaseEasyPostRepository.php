<?php

namespace App\Repositories\EasyPost;


use App\Integrations\EasyPost\EasyPostConfiguration;
use App\Integrations\EasyPost\EasyPostIntegration;
use App\Models\CMS\Client;
use App\Models\Integrations\IntegratedShippingApi;
use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Services\CredentialService;

class BaseEasyPostRepository
{

    /**
     * @var EasyPostIntegration
     */
    protected $easyPostIntegration;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var IntegratedShippingApi
     */
    protected $integratedShippingApi;

    /**
     * @var ClientRepository
     */
    protected $clientRepo;


    public function __construct(IntegratedShippingApi $integratedShippingApi)
    {
        $this->integratedShippingApi= $integratedShippingApi;
        $credentialService          = new CredentialService($this->integratedShippingApi);
        $apiKey                     = $credentialService->getEasyPostApiKey()->getValue();

        $easyPostConfiguration      = new EasyPostConfiguration();
        $easyPostConfiguration->setApiKey($apiKey);
        dd($apiKey);
        $this->easyPostIntegration  = new EasyPostIntegration($easyPostConfiguration);
    }
}