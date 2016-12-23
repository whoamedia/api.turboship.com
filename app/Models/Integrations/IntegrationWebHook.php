<?php

namespace App\Models\Integrations;


use jamesvweston\Utilities\ArrayUtil AS AU;

class IntegrationWebHook implements \JsonSerializable
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
    protected $topic;

    /**
     * @var bool
     */
    protected $isActive;


    public function __construct($data = [])
    {
        $this->topic                    = AU::get($data['topic']);
        $this->isActive                 = AU::get($data['isActive']);
        $this->integration              = AU::get($data['integration']);
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['topic']                = $this->topic;
        $object['isActive']             = $this->isActive;

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
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

}