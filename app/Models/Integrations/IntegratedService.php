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
     * @var string;
     */
    protected $name;

    /**
     * @var Integration
     */
    protected $integration;

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

        $this->name                     = AU::get($data['name']);
        $this->integration              = AU::get($data['integration']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['integration']          = $this->integration->jsonSerialize();
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return IntegrationWebHook[]
     */
    public function getAvailableIntegratedWebHooks ()
    {
        $available                  = [];
        foreach ($this->getIntegration()->getIntegrationWebHooks() AS $integrationWebHook)
        {
            if (!$integrationWebHook->isActive())
                continue;

            if (!$this->hasIntegrationWebHook($integrationWebHook))
                $available[]        = $integrationWebHook;
        }

        return $available;
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