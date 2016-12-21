<?php

namespace App\Models\Logs;


use App\Models\Integrations\ClientIntegration;
use App\Models\Integrations\IntegrationWebHook;
use jamesvweston\Utilities\ArrayUtil AS AU;

class WebHookLog
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ClientIntegration
     */
    protected $clientIntegration;

    /**
     * @var IntegrationWebHook
     */
    protected $integrationWebHook;

    /**
     * @var string
     */
    protected $incomingMessage;

    /**
     * @var bool
     */
    protected $verified;


    public function __construct($data = [])
    {
        $this->clientIntegration        = AU::get($data['clientIntegration']);
        $this->integrationWebHook       = AU::get($data['integrationWebHook']);
        $this->incomingMessage          = AU::get($data['incomingMessage']);
        $this->verified                 = AU::get($data['verified']);
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

    /**
     * @return string
     */
    public function getIncomingMessage()
    {
        return $this->incomingMessage;
    }

    /**
     * @param string $incomingMessage
     */
    public function setIncomingMessage($incomingMessage)
    {
        $this->incomingMessage = $incomingMessage;
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param boolean $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

}