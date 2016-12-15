<?php

namespace App\Utilities;


use App\Models\OMS\Validation\OrderSourceValidation;

class OrderSourceUtility
{

    const INTERNAL_ID               = 1;
    const SHOPIFY_ID                = 2;


    /**
     * @var OrderSourceValidation
     */
    private $orderSourceValidation;


    public function __construct()
    {
        $this->orderSourceValidation    = new OrderSourceValidation();
    }

    /**
     * @return \App\Models\OMS\OrderSource
     */
    public function getInternal ()
    {
        return $this->orderSourceValidation->idExists(self::INTERNAL_ID);
    }

    /**
     * @return \App\Models\OMS\OrderSource
     */
    public function getShopify ()
    {
        return $this->orderSourceValidation->idExists(self::SHOPIFY_ID);
    }

}