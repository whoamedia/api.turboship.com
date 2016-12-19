<?php

namespace App\Utilities;


use App\Models\OMS\Validation\CRMSourceValidation;

class CRMSourceUtility
{

    const INTERNAL_ID               = 1;
    const SHOPIFY_ID                = 2;


    /**
     * @var CRMSourceValidation
     */
    private $crmSourceValidation;


    public function __construct()
    {
        $this->crmSourceValidation      = new CRMSourceValidation();
    }

    /**
     * @return \App\Models\OMS\CRMSource
     */
    public function getInternal ()
    {
        return $this->crmSourceValidation->idExists(self::INTERNAL_ID);
    }

    /**
     * @return \App\Models\OMS\CRMSource
     */
    public function getShopify ()
    {
        return $this->crmSourceValidation->idExists(self::SHOPIFY_ID);
    }

}