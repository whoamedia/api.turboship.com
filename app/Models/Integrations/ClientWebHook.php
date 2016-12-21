<?php

namespace App\Models\Integrations;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ClientWebHook implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var ClientIntegration
     */
    protected $clientIntegration;

    /**
     * @var IntegrationWebHook
     */
    protected $integrationWebHook;


    public function __construct($data = [])
    {
        $this->externalId               = AU::get($data['externalId']);
        $this->clientIntegration        = AU::get($data['clientIntegration']);
        $this->integrationWebHook       = AU::get($data['integrationWebHook']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;

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
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return ClientIntegration
     */
    public function getClientIntegration()
    {
        return $this->clientIntegration;
    }

    /**
     * @param ClientIntegration $clientIntegration
     */
    public function setClientIntegration($clientIntegration)
    {
        $this->clientIntegration = $clientIntegration;
    }

    /**
     * @return IntegrationWebHook
     */
    public function getIntegrationWebHook()
    {
        return $this->integrationWebHook;
    }

    /**
     * @param IntegrationWebHook $integrationWebHook
     */
    public function setIntegrationWebHook($integrationWebHook)
    {
        $this->integrationWebHook = $integrationWebHook;
    }

}