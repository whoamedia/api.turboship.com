<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#addresses
 * Class Verification
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostVerification
{

    use SimpleSerialize;

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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}