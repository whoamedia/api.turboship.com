<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#carrier-account-object
 * Class Fields
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostFields
{

    /**
     * Credentials used in the production environment.
     * @var	EasyPostField
     */
    protected $credentials;

    /**
     * Credentials used in the test environment.
     * @var	EasyPostField
     */
    protected $test_credentials;

    /**
     * For USPS this designates that no credentials are required.
     * @var	boolean
     */
    protected $auto_link;

    /**
     * When present, a seperate authentication process will be required through the UI to link this account type.
     * @var	boolean
     */
    protected $custom_workflow;


}