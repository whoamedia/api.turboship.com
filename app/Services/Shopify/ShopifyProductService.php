<?php

namespace App\Services\Shopify;


use App\Models\CMS\Client;
use App\Repositories\Doctrine\OMS\ProductRepository;
use EntityManager;

class ShopifyProductService
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ProductRepository
     */
    private $productRepo;


    public function __construct(Client $client)
    {
        $this->client                   = $client;
        $this->productRepo              = EntityManager::getRepository('App\Models\OMS\Product');
        $this->shopifyMappingService    = new ShopifyMappingService();
    }


    public function download ()
    {

    }

}