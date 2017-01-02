<?php

namespace App\Services\Shopify\Mapping;


use App\Models\CMS\Client;
use App\Models\Support\Source;
use App\Models\Support\Validation\SourceValidation;
use App\Services\WeightConversionService;

class BaseShopifyMappingService
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Source
     */
    protected $shopifySource;

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
        $sourceValidation               = new SourceValidation();
        $this->shopifySource            = $sourceValidation->getShopify();
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