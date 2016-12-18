<?php

namespace App\Exceptions\Address;


use App\Utilities\OrderStatusUtility;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddressNotFoundException extends BadRequestHttpException
{

    public function __construct($message = 'Invalid Address', \Exception $previous = null)
    {
        parent::__construct($message, $previous, OrderStatusUtility::INVALID_ADDRESS_ID);
    }

}