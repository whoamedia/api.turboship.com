<?php

namespace App\Models\CMS;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ClientOption implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string|null
     */
    protected $defaultShipToPhone;


    public function __construct($data = [])
    {
        $this->client                   = AU::get($data['client']);
        $this->defaultShipToPhone       = AU::get($data['defaultShipToPhone']);
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['defaultShipToPhone']   = $this->defaultShipToPhone;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getDefaultShipToPhone()
    {
        return $this->defaultShipToPhone;
    }

    /**
     * @param null|string $defaultShipToPhone
     */
    public function setDefaultShipToPhone($defaultShipToPhone)
    {
        $this->defaultShipToPhone = $defaultShipToPhone;
    }

}