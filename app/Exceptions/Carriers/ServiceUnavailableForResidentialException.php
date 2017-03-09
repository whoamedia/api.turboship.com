<?php

namespace App\Exceptions\Carriers;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceUnavailableForResidentialException extends HttpException
{

    public function __construct($message = 'The selected service is unavailable for residential addresses', \Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct(400, $message, $previous, $headers, $code);
    }

}