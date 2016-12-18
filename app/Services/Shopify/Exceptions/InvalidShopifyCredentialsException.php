<?php

namespace App\Services\Shopify\Exceptions;


class InvalidShopifyCredentialsException extends \Exception
{

    public function __construct($message = 'Invalid shopify credentials', $code = 403, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}