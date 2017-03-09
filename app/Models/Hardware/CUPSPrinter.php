<?php

namespace App\Models\Hardware;


use jamesvweston\Utilities\ArrayUtil AS AU;

class CUPSPrinter extends Printer implements \JsonSerializable
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
     * @var string
     */
    protected $format;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->address                  = AU::get($data['address']);
        $this->port                     = AU::get($data['port']);
        $this->format                   = AU::get($data['format']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['address']              = $this->address;
        $object['port']                 = $this->port;
        $object['format']               = $this->format;

        return $object;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'CUPSPrinter';
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
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

}