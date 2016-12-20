<?php

namespace App\Models\Integrations;


use App\Models\CMS\Client;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ClientIntegration implements \JsonSerializable
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
     * @var Integration
     */
    protected $integration;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var ArrayCollection
     */
    protected $credentials;


    /**
     * ClientIntegration constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->credentials              = new ArrayCollection();

        $this->client                   = AU::get($data['client']);
        $this->integration              = AU::get($data['integration']);
        $this->symbol                   = AU::get($data['symbol']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['client']               = $this->client->jsonSerialize();
        $object['integration']          = $this->integration->jsonSerialize();
        $object['symbol']               = $this->symbol;
        $object['createdAt']            = $this->createdAt;

        return $object;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Integration
     */
    public function getIntegration()
    {
        return $this->integration;
    }

    /**
     * @param Integration $integration
     */
    public function setIntegration($integration)
    {
        $this->integration = $integration;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param ClientCredential $credential
     */
    public function addCredential (ClientCredential $credential)
    {
        $credential->setClientIntegration($this);
        $this->credentials->add($credential);
    }

    /**
     * @return ClientCredential[]
     */
    public function getCredentials ()
    {
        return $this->credentials->toArray();
    }

}