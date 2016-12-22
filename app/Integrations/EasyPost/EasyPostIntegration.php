<?php

namespace App\Integrations\EasyPost;


use App\Integrations\EasyPost\Api\AddressApi;

class EasyPostIntegration
{

    /**
     * @var EasyPostConfiguration
     */
    protected $easyPostConfiguration;

    /**
     * @var AddressApi
     */
    public $addressApi;

    public function __construct(EasyPostConfiguration $easyPostConfiguration)
    {
        $this->easyPostConfiguration    = $easyPostConfiguration;

        $this->addressApi               = new AddressApi($this->easyPostConfiguration);
    }

    /**
     * @return EasyPostConfiguration
     */
    public function getEasyPostConfiguration()
    {
        return $this->easyPostConfiguration;
    }

    /**
     * @param EasyPostConfiguration $easyPostConfiguration
     */
    public function setEasyPostConfiguration($easyPostConfiguration)
    {
        $this->easyPostConfiguration = $easyPostConfiguration;
    }

}