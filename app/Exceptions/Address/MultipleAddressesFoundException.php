<?php

namespace App\Exceptions\Address;


use App\Utilities\OrderStatusUtility;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MultipleAddressesFoundException extends BadRequestHttpException
{

    public function __construct($message = 'Multiple Addresses Found', \Exception $previous = null)
    {
        parent::__construct($message, $previous, OrderStatusUtility::MULTIPLE_ADDRESSES_FOUND_ID);
    }

}