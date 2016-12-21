<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateClientCredential implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $integrationCredentialId;


    public function __construct($data = [])
    {
        $this->value                    = AU::get($data['value']);
        $this->integrationCredentialId  = AU::get($data['integrationCredentialId']);
    }

    public function validate()
    {
        if (is_null($this->value))
            throw new BadRequestHttpException('value is required');

        if (is_null($this->integrationCredentialId))
            throw new BadRequestHttpException('integrationCredentialId is required');


        if (empty(trim($this->value)))
            throw new BadRequestHttpException('value must be string');
        if (is_null(InputUtil::getInt($this->integrationCredentialId)))
            throw new BadRequestHttpException('integrationCredentialId must be integer');
    }

    public function clean ()
    {
        $this->integrationCredentialId  = InputUtil::getInt($this->integrationCredentialId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['value']                    = $this->value;
        $object['integrationCredentialId']  = $this->integrationCredentialId;

        return $object;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getIntegrationCredentialId()
    {
        return $this->integrationCredentialId;
    }

    /**
     * @param int $integrationCredentialId
     */
    public function setIntegrationCredentialId($integrationCredentialId)
    {
        $this->integrationCredentialId = $integrationCredentialId;
    }

}