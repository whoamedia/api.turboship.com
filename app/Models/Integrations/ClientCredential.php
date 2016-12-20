<?php

namespace App\Models\Integrations;


use jamesvweston\Utilities\ArrayUtil AS AU;
use Crypt;

class ClientCredential
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var IntegrationCredential
     */
    protected $integrationCredential;

    /**
     * @var ClientIntegration
     */
    protected $clientIntegration;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->value                    = AU::get($data['value']);
        $this->integrationCredential    = AU::get($data['integrationCredential']);
        $this->clientIntegration        = AU::get($data['clientIntegration']);

        if (!is_null($this->value))
            $this->setValue($this->value);
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
    public function getValue()
    {
        return Crypt::decrypt($this->value);
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value                    = Crypt::encrypt($value);
    }

    /**
     * @return IntegrationCredential
     */
    public function getIntegrationCredential()
    {
        return $this->integrationCredential;
    }

    /**
     * @param IntegrationCredential $integrationCredential
     */
    public function setIntegrationCredential($integrationCredential)
    {
        $this->integrationCredential = $integrationCredential;
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}