<?php

namespace App\Models\Integrations;


use App\Models\CMS\Client;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Crypt;

class ClientCredential
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var IntegrationCredential
     */
    protected $integrationCredential;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->value                    = AU::get($data['value']);
        $this->integrationCredential    = AU::get($data['integrationCredential']);
        $this->client                   = AU::get($data['client']);

        if (!is_null($this->value))
            $this->setValue($this->value);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return Crypt::decrypt($this->value);
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value                    = Crypt::encrypt($value);
    }

    /**
     * @return IntegrationCredential
     */
    public function getIntegrationCredential()
    {
        return $this->integrationCredential;
    }

    /**
     * @param IntegrationCredential $integrationCredential
     */
    public function setIntegrationCredential($integrationCredential)
    {
        $this->integrationCredential = $integrationCredential;
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}