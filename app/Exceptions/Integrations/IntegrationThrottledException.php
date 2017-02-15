<?php

namespace App\Exceptions\Integrations;


use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class IntegrationThrottledException extends TooManyRequestsHttpException
{

    public function __construct($message, $retryAfter = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($retryAfter, $message, $previous, $code);
    }
}