<?php

namespace App\Exceptions\Integrations;


use Symfony\Component\HttpKernel\Exception\HttpException;

class IntegrationInvalidCredentialsException extends HttpException
{

    public function __construct($message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct(403, $message, $previous, $headers, $code);
    }

}