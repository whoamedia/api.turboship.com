<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#addresses
 * Class Verification
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostVerification
{

    /**
     * The success of the verification
     * @var bool
     */
    protected $success;

    /**
     * All errors that caused the verification to fail
     * @var EasyPostFieldError[]
     */
    protected $errors;

    /**
     * @var EasyPostVerificationDetails
     */
    protected $details;
}