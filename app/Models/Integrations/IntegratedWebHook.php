<?php

namespace App\Models\Integrations;


use jamesvweston\Utilities\ArrayUtil AS AU;

class IntegratedWebHook implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $externalId;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var IntegratedService
     */
    protected $integratedService;

    /**
     * @var IntegrationWebHook
     */
    protected $integrationWebHook;


    public function __construct($data = [])
    {
        $this->externalId               = AU::get($data['externalId']);
        $this->url                      = AU::get($data['url']);
        $this->integratedService        = AU::get($data['integratedService']);
        $this->integrationWebHook       = AU::get($data['integrationWebHook']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['externalId']           = $this->externalId;
        $object['integrationWebHook']   = $this->integrationWebHook->jsonSerialize();

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
     * @return null|string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param null|string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return IntegratedService
     */
    public function getIntegratedService()
    {
        return $this->integratedService;
    }

    /**
     * @param IntegratedService $integratedService
     */
    public function setIntegratedService($integratedService)
    {
        $this->integratedService = $integratedService;
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