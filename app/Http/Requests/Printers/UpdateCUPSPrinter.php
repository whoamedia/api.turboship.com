<?php

namespace App\Http\Requests\Printers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;

class UpdateCUPSPrinter extends UpdatePrinter implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $address;

    /**
     * @var string|null
     */
    protected $port;

    /**
     * @var string|null
     */
    protected $format;

    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->address                  = AU::get($data['address']);
        $this->port                     = AU::get($data['port']);
        $this->format                   = AU::get($data['format']);
    }

    public function validate()
    {
        parent::validate();
    }

    public function clean ()
    {
        parent::clean();
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['address']              = $this->address;
        $object['port']                 = $this->port;
        $object['format']               = $this->format;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param null|string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return null|string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param null|string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return null|string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param null|string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

}