<?php

namespace App\Models\Integrations;


use App\Models\CMS\Client;
use jamesvweston\Utilities\ArrayUtil AS AU;

class IntegratedShoppingCart extends IntegratedService
{

    /**
     * @var Client
     */
    protected $client;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->client                   = AU::get($data['client']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['client']               = $this->client->jsonSerialize();

        return $object;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

}