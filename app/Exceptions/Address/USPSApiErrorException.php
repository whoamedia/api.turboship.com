<?php

namespace App\Exceptions\Address;


class USPSApiErrorException extends \Exception
{

    public function __construct($message = 'USPS Address Validation Failure', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}