<?php

namespace App\Models\Integrations;


use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

abstract class IntegratedService implements \JsonSerializable
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
    protected $integratedWebHooks;


    /**
     * IntegratedService constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->credentials              = new ArrayCollection();
        $this->integratedWebHooks       = new ArrayCollection();

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
     * @param Credential $credential
     */
    public function addCredential (Credential $credential)
    {
        $credential->setIntegratedService($this);
        $this->credentials->add($credential);
    }

    /**
     * @return Credential[]
     */
    public function getCredentials ()
    {
        return $this->credentials->toArray();
    }

    /**
     * @return IntegratedWebHook[]
     */
    public function getIntegratedWebHooks ()
    {
        return $this->integratedWebHooks->toArray();
    }

    /**
     * @param   IntegratedWebHook $integratedWebHook
     * @return  bool
     */
    public function hasIntegratedWebHook (IntegratedWebHook $integratedWebHook)
    {
        return $this->integratedWebHooks->contains($integratedWebHook);
    }

    /**
     * Does the IntegratedService already have the webHook registered?
     * @param   IntegrationWebHook $integrationWebHook
     * @return  bool
     */
    public function hasIntegrationWebHook (IntegrationWebHook $integrationWebHook)
    {
        foreach ($this->getIntegratedWebHooks() AS $integratedWebHook)
        {
            if ($integratedWebHook->getIntegrationWebHook()->getId() == $integrationWebHook->getId())
                return true;
        }
        return false;
    }

    /**
     * @param IntegratedWebHook $integratedWebHook
     */
    public function addIntegratedWebHook (IntegratedWebHook $integratedWebHook)
    {
        $integratedWebHook->setIntegratedService($this);
        $this->integratedWebHooks->add($integratedWebHook);
    }

    /**
     * @param IntegratedWebHook $integratedWebHook
     */
    public function removeIntegratedWebHook (IntegratedWebHook $integratedWebHook)
    {
        $this->integratedWebHooks->removeElement($integratedWebHook);
    }

}