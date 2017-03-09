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
    protected $integrationWebHooks;


    public function __construct($data = [])
    {
        $this->integrationCredentials   = new ArrayCollection();
        $this->integrationWebHooks      = new ArrayCollection();

        $this->name                     = AU::get($data['name']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['object']               = $this->getObject();

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
     * @param   int     $id
     * @return IntegrationCredential|null
     */
    public function getIntegrationCredentialById ($id)
    {
        foreach ($this->getIntegrationCredentials() AS $item)
        {
            if ($item->getId() == $id)
                return $item;
        }
        return null;
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
    public function getIntegrationWebHooks ()
    {
        return $this->integrationWebHooks->toArray();
    }

    /**
     * @param   IntegrationWebHook $integrationWebHook
     * @return  bool
     */
    public function hasIntegrationWebHook (IntegrationWebHook $integrationWebHook)
    {
        return $this->integrationWebHooks->contains($integrationWebHook);
    }

    /**
     * @param   IntegrationWebHook $integrationWebHook
     */
    public function addIntegrationWebHook (IntegrationWebHook $integrationWebHook)
    {
        $integrationWebHook->setIntegration($this);
        $this->integrationWebHooks->add($integrationWebHook);
    }

    /**
     * @return string
     */
    abstract function getObject ();

}