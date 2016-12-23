<?php

namespace App\Integrations\EasyPost;


use App\Integrations\EasyPost\Api\AddressApi;
use App\Integrations\EasyPost\Api\BatchApi;
use App\Integrations\EasyPost\Api\InsuranceApi;
use App\Integrations\EasyPost\Api\ParcelApi;
use App\Integrations\EasyPost\Api\ShipmentApi;
use App\Integrations\EasyPost\Api\TrackerApi;

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

    /**
     * @var BatchApi
     */
    public $batchApi;

    /**
     * @var InsuranceApi
     */
    public $insuranceApi;

    /**
     * @var ParcelApi
     */
    public $parcelApi;

    /**
     * @var ShipmentApi
     */
    public $shipmentApi;

    /**
     * @var TrackerApi
     */
    public $trackerApi;

    public function __construct(EasyPostConfiguration $easyPostConfiguration)
    {
        $this->easyPostConfiguration    = $easyPostConfiguration;

        $this->addressApi               = new AddressApi($this->easyPostConfiguration);
        $this->batchApi                 = new BatchApi($this->easyPostConfiguration);
        $this->insuranceApi             = new InsuranceApi($this->easyPostConfiguration);
        $this->parcelApi                = new ParcelApi($this->easyPostConfiguration);
        $this->shipmentApi              = new ShipmentApi($this->easyPostConfiguration);
        $this->trackerApi               = new TrackerApi($this->easyPostConfiguration);
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