<?php

namespace App\Integrations\EasyPost;


use App\Integrations\EasyPost\Api\AddressApi;
use App\Integrations\EasyPost\Api\ApiKeyApi;
use App\Integrations\EasyPost\Api\BatchApi;
use App\Integrations\EasyPost\Api\CarrierAccountApi;
use App\Integrations\EasyPost\Api\CarrierTypeApi;
use App\Integrations\EasyPost\Api\CustomsInfoApi;
use App\Integrations\EasyPost\Api\CustomsItemApi;
use App\Integrations\EasyPost\Api\InsuranceApi;
use App\Integrations\EasyPost\Api\OrderApi;
use App\Integrations\EasyPost\Api\ParcelApi;
use App\Integrations\EasyPost\Api\PickupApi;
use App\Integrations\EasyPost\Api\ShipmentApi;
use App\Integrations\EasyPost\Api\TrackerApi;
use App\Integrations\EasyPost\Api\UserApi;

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
     * @var ApiKeyApi
     */
    public $apiKeyApi;

    /**
     * @var BatchApi
     */
    public $batchApi;

    /**
     * @var CarrierAccountApi
     */
    public $carrierAccountApi;

    /**
     * @var CarrierTypeApi
     */
    public $carrierTypeApi;

    /**
     * @var CustomsInfoApi
     */
    public $customsInfoApi;

    /**
     * @var CustomsItemApi
     */
    public $customsItemApi;

    /**
     * @var InsuranceApi
     */
    public $insuranceApi;

    /**
     * @var OrderApi
     */
    public $orderApi;

    /**
     * @var ParcelApi
     */
    public $parcelApi;

    /**
     * @var PickupApi
     */
    public $pickupApi;

    /**
     * @var ShipmentApi
     */
    public $shipmentApi;

    /**
     * @var TrackerApi
     */
    public $trackerApi;

    /**
     * @var UserApi
     */
    public $userApi;

    public function __construct(EasyPostConfiguration $easyPostConfiguration)
    {
        $this->easyPostConfiguration    = $easyPostConfiguration;

        $this->addressApi               = new AddressApi($this->easyPostConfiguration);
        $this->apiKeyApi                = new ApiKeyApi($this->easyPostConfiguration);
        $this->batchApi                 = new BatchApi($this->easyPostConfiguration);
        $this->carrierAccountApi        = new CarrierAccountApi($this->easyPostConfiguration);
        $this->carrierTypeApi           = new CarrierTypeApi($this->easyPostConfiguration);
        $this->customsInfoApi           = new CustomsInfoApi($this->easyPostConfiguration);
        $this->customsItemApi           = new CustomsItemApi($this->easyPostConfiguration);
        $this->insuranceApi             = new InsuranceApi($this->easyPostConfiguration);
        $this->orderApi                 = new OrderApi($this->easyPostConfiguration);
        $this->parcelApi                = new ParcelApi($this->easyPostConfiguration);
        $this->pickupApi                = new PickupApi($this->easyPostConfiguration);
        $this->shipmentApi              = new ShipmentApi($this->easyPostConfiguration);
        $this->trackerApi               = new TrackerApi($this->easyPostConfiguration);
        $this->userApi                  = new UserApi($this->easyPostConfiguration);
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