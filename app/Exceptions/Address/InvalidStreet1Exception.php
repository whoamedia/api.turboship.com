<?php

namespace App\Exceptions\Address;


use App\Utilities\OrderStatusUtility;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidStreet1Exception extends BadRequestHttpException
{

    public function __construct($message = 'Invalid Street1', \Exception $previous = null)
    {
        parent::__construct($message, $previous, OrderStatusUtility::INVALID_STREET_ID);
    }

}