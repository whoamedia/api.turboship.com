<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#message-object
 * Class Message
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostMessage
{

    /**
     * 	the name of the carrier generating the error, e.g. "UPS"
     * @var string
     */
    protected $carrier;

    /**
     * the category of error that occurred. Most frequently "rate_error"
     * @var string
     */
    protected $type;

    /**
     * the string from the carrier explaining the problem.
     * @var string
     */
    protected $message;
}