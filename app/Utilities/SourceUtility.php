<?php

namespace App\Utilities;


use App\Models\Support\Validation\SourceValidation;

class SourceUtility
{

    const INTERNAL_ID               = 1;
    const SHOPIFY_ID                = 2;


    /**
     * @var SourceValidation
     */
    private $sourceValidation;


    public function __construct()
    {
        $this->sourceValidation         = new SourceValidation();
    }



}