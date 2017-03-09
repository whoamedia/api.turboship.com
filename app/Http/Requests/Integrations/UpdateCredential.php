<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateCredential implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $integrationCredentialId;

    /**
     * @var string
     */
    protected $value;


    public function __construct($data = [])
    {
        $this->integrationCredentialId  = AU::get($data['integrationCredentialId']);
        $this->value                    = AU::get($data['value']);
    }

    public function validate()
    {
        if (is_null($this->integrationCredentialId))
            throw new BadRequestHttpException('integrationCredentialId is required');

        if (is_null($this->value))
            throw new BadRequestHttpException('value is required');


        if (is_null(InputUtil::getInt($this->integrationCredentialId)))
            throw new BadRequestHttpException('integrationCredentialId must be integer');

        if (empty($this->value))
            throw new BadRequestHttpException('value cannot be empty');
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
        $object['integrationCredentialId'] = $this->integrationCredentialId;
        $object['value']                = $this->value;

        return $object;
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

}