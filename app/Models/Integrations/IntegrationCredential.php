<?php

namespace App\Models\Integrations;


class IntegrationCredential
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * @var Integration
     */
    protected $integration;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['isRequired']           = $this->isRequired;

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
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * @return Integration
     */
    public function getIntegration()
    {
        return $this->integration;
    }


}