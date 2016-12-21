<?php

namespace App\Models\Integrations;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @SWG\Definition(@SWG\Xml())
 */
class Integration implements \JsonSerializable
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
     * @return IntegrationCredential[]
     */
    public function getIntegrationCredentials ()
    {
        return $this->integrationCredentials->toArray();
    }

    /**
     * @return IntegrationWebHook[]
     */
    public function getWebHooks ()
    {
        return $this->webHooks->toArray();
    }

}