<?php

namespace App\Exceptions\Integrations;


use Symfony\Component\HttpKernel\Exception\HttpException;


class IntegrationNotRespondingException extends HttpException
{

    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message, \Exception $previous = null, $code = 0)
    {
        parent::__construct(503, $message, $previous, array(), $code);
    }

}