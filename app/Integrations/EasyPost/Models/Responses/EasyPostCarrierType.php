<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#carrier-types
 * Class CarrierType
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostCarrierType
{

    /**
     * "CarrierType"
     * @var	string
     */
    protected $object;

    /**
     * Specifies the CarrierAccount type.
     * Note that "EndiciaAccount" is the current USPS integration account type
     * @var	string
     */
    protected $type;

    /**
     * Contains at least one of the following keys:
     * "auto_link", "credentials", "test_credentials", and "custom_workflow"
     * @var	EasyPostFields
     */
    protected $fields;


}