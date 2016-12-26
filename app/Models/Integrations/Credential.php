<?php

namespace App\Models\Integrations;


use jamesvweston\Utilities\ArrayUtil AS AU;
use Crypt;

class Credential
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
     * @var IntegratedService
     */
    protected $integratedService;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->value                    = AU::get($data['value']);
        $this->integrationCredential    = AU::get($data['integrationCredential']);
        $this->integratedService        = AU::get($data['integratedService']);

        if (!is_null($this->value))
            $this->setValue($this->value);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['integrationCredential']= $this->integrationCredential->jsonSerialize();
        $object['value']                = $this->getValue();
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}