<?php

namespace App\Exceptions\Address;


use App\Utilities\OrderStatusUtility;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidCityException extends BadRequestHttpException
{

    public function __construct($message = 'Invalid City', \Exception $previous = null)
    {
        parent::__construct($message, $previous, OrderStatusUtility::INVALID_COUNTRY_ID);
    }

}