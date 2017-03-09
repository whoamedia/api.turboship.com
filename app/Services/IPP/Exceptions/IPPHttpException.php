<?php

namespace App\Services\IPP\Exceptions;


class IPPHttpException extends \Exception
{
    protected $errno;

    public function __construct($msg, $errno = null)
    {
        parent::__construct($msg);
        $this->errno = $errno;
    }

    public function getErrorFormatted()
    {
        return sprintf("[http_class]: %s -- " . _(" file %s, line %s"),
            $this->getMessage(), $this->getFile(), $this->getLine());
    }

    public function getErrno()
    {
        return $this->errno;
    }
}