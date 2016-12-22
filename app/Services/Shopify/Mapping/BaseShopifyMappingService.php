<?php

namespace App\Services\Shopify\Mapping;


use App\Models\CMS\Client;
use App\Models\OMS\CRMSource;
use App\Models\OMS\Validation\CRMSourceValidation;
use App\Services\WeightConversionService;
use App\Utilities\CRMSourceUtility;

class BaseShopifyMappingService
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var CRMSource
     */
    protected $shopifyCRMSource;

    /**
     * @var WeightConversionService
     */
    protected $weightConversionService;

    /**
     * @var ShopifyMappingExceptionService
     */
    protected $shopifyMappingExceptionService;


    public function __construct(Client $client)
    {
        $this->client                   = $client;
        $crmSourceValidation            = new CRMSourceValidation();
        $this->shopifyCRMSource         = $crmSourceValidation->idExists(CRMSourceUtility::SHOPIFY_ID);
        $this->weightConversionService  = new WeightConversionService();
        $this->shopifyMappingExceptionService = new ShopifyMappingExceptionService();
    }


    /**
     * @param   string|null     $shopifyDate
     * @return  \DateTime|null
     */
    public function toDate ($shopifyDate)
    {
        if (is_null($shopifyDate))
            return null;
        else
            return \DateTime::createFromFormat('Y-m-d\TH:i:sO', $shopifyDate);
    }

}