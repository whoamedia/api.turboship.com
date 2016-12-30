<?php
/**
 * Created by IntelliJ IDEA.
 * User: jamesweston
 * Date: 12/30/16
 * Time: 12:07 AM
 */

namespace App\Integrations\EasyPost\Exceptions;


class EasyPostReferenceRequiredException extends EasyPostApiException
{

    public function __construct($message = 'Reference field is required', $code = 400, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}