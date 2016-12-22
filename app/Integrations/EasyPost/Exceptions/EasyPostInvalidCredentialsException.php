<?php

namespace App\Integrations\EasyPost\Exceptions;


class EasyPostInvalidCredentialsException extends \Exception
{

    public function __construct($message = 'Invalid shopify credentials', $code = 403, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}