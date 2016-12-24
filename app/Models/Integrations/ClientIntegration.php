<?php

namespace App\Models\Integrations;


use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

abstract class ClientIntegration implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

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
     * @var ArrayCollection
     */
    protected $webHooks;


    /**
     * ClientIntegration constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->credentials              = new ArrayCollection();
        $this->webHooks                 = new ArrayCollection();

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
        $object['integration']          = $this->integration->jsonSerialize();
        $object['symbol']               = $this->symbol;
        $object['createdAt']            = $this->createdAt;

        $object['credentials']          = [];
        foreach ($this->getCredentials() AS $credential)
        {
            $object['credentials'][]    = $credential->jsonSerialize();
        }

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

    /**
     * @return ClientWebHook[]
     */
    public function getWebHooks ()
    {
        return $this->webHooks->toArray();
    }

    /**
     * @param   ClientWebHook $clientWebHook
     * @return  bool
     */
    public function hasWebHook (ClientWebHook $clientWebHook)
    {
        return $this->webHooks->contains($clientWebHook);
    }

    /**
     * Does the ClientIntegration already have the webHook registered?
     * @param   IntegrationWebHook $integrationWebHook
     * @return  bool
     */
    public function hasIntegrationWebHook (IntegrationWebHook $integrationWebHook)
    {
        foreach ($this->getWebHooks() AS $clientWebHook)
        {
            if ($clientWebHook->getIntegrationWebHook()->getId() == $integrationWebHook->getId())
                return true;
        }
        return false;
    }

    /**
     * @param ClientWebHook $clientWebHook
     */
    public function addWebHook (ClientWebHook $clientWebHook)
    {
        $clientWebHook->setClientIntegration($this);
        $this->webHooks->add($clientWebHook);
    }

    /**
     * @param ClientWebHook $clientWebHook
     */
    public function removeWebHook (ClientWebHook $clientWebHook)
    {
        $this->webHooks->removeElement($clientWebHook);
    }

}