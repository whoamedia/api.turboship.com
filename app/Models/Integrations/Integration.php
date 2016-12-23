<?php

namespace App\Models\Integrations;


use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @SWG\Definition(@SWG\Xml())
 */
abstract class Integration implements \JsonSerializable
{

    /**
     * @SWG\Property(example="1")
     * @var     int
     */
    protected $id;

    /**
     * @SWG\Property(example="Shopify")
     * @var     string
     */
    protected $name;

    /**
     * @var ArrayCollection
     */
    protected $integrationCredentials;

    /**
     * @var ArrayCollection
     */
    protected $webHooks;


    public function __construct($data = [])
    {
        $this->integrationCredentials   = new ArrayCollection();
        $this->webHooks                 = new ArrayCollection();

        $this->name                     = AU::get($data['name']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;

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
     * @return IntegrationCredential[]
     */
    public function getIntegrationCredentials ()
    {
        return $this->integrationCredentials->toArray();
    }

    /**
     * @param   IntegrationCredential $integrationCredential
     */
    public function addIntegrationCredential (IntegrationCredential $integrationCredential)
    {
        $integrationCredential->setIntegration($this);
        $this->integrationCredentials->add($integrationCredential);
    }

    /**
     * @param   IntegrationCredential $integrationCredential
     * @return  bool
     */
    public function hasIntegrationCredential (IntegrationCredential $integrationCredential)
    {
        return $this->integrationCredentials->contains($integrationCredential);
    }

    /**
     * @return IntegrationWebHook[]
     */
    public function getWebHooks ()
    {
        return $this->webHooks->toArray();
    }

    /**
     * @param   IntegrationWebHook $integrationWebHook
     * @return  bool
     */
    public function hasWebHook (IntegrationWebHook $integrationWebHook)
    {
        return $this->webHooks->contains($integrationWebHook);
    }

    /**
     * @param   IntegrationWebHook $integrationWebHook
     */
    public function addIntegrationWebHook (IntegrationWebHook $integrationWebHook)
    {
        $integrationWebHook->setIntegration($this);
        $this->webHooks->add($integrationWebHook);
    }

    /**
     * @return string
     */
    abstract function getObject ();

}