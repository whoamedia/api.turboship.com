<?php

namespace App\Http\Requests\Printers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateCUPSPrinter extends CreatePrinter implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
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

        if (is_null($this->address))
            throw new BadRequestHttpException('address is required');

        if (is_null($this->format))
            throw new BadRequestHttpException('format is required');


        if (empty(trim($this->address)))
            throw new BadRequestHttpException('address cannot be empty');

        if (empty(trim($this->format)))
            throw new BadRequestHttpException('format cannot be empty');

        if (strtoupper($this->format) != 'ZPL')
            throw new BadRequestHttpException('invalid format');
    }

    public function clean ()
    {
        parent::clean();

        $this->format                   = strtoupper($this->format);
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
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
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