<?php

namespace App\Exceptions\Country;


use App\Utilities\OrderStatusUtility;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidSubdivisionException extends BadRequestHttpException
{

    public function __construct($message = 'Invalid Subdivision', \Exception $previous = null)
    {
        parent::__construct($message, $previous, OrderStatusUtility::INVALID_STATE_ID);
    }

}