<?php

namespace App\Services\IPP\Exceptions;


class IPPException extends \Exception
{
    protected $errno;

    public function __construct($msg, $errno = null)
    {
        parent::__construct($msg);
        $this->errno = $errno;
    }

    public function getErrorFormatted()
    {
        $return = sprintf("[ipp]: %s -- " . _(" file %s, line %s"),
            $this->getMessage(), $this->getFile(), $this->getLine());
        return $return;
    }

    public function getErrno()
    {
        return $this->errno;
    }
}