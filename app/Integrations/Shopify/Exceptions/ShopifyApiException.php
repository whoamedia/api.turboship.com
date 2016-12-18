<?php

namespace App\Integrations\Shopify\Exceptions;


class ShopifyApiException extends \Exception
{

    public function __construct($message, $code, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}