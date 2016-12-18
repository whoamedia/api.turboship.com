<?php

namespace App\Services\Shopify\Exceptions;


class ShopifyBadRequestException extends \Exception
{

    public function __construct($message = 'Required parameter missing or invalid', $code = 400, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}