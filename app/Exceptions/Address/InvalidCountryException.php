<?php

namespace App\Exceptions\Country;


use App\Utilities\OrderStatusUtility;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidCountryException extends BadRequestHttpException
{

    public function __construct($message = 'Invalid Country', \Exception $previous = null)
    {
        parent::__construct($message, $previous, OrderStatusUtility::INVALID_COUNTRY_ID);
    }

}