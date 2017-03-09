<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateWebHook implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $integrationWebHookId;

    public function __construct($data = [])
    {
        $this->integrationWebHookId     = AU::get($data['integrationWebHookId']);
    }

    public function validate()
    {
        if (is_null($this->integrationWebHookId))
            throw new BadRequestHttpException('integrationWebHookId is required');

        if (is_null(InputUtil::getInt($this->integrationWebHookId)))
            throw new BadRequestHttpException('integrationWebHookId must be integer');
    }

    public function clean ()
    {
        $this->integrationWebHookId     = InputUtil::getInt($this->integrationWebHookId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['integrationWebHookId']  = $this->integrationWebHookId;

        return $object;
    }

    /**
     * @return int
     */
    public function getIntegrationWebHookId()
    {
        return $this->integrationWebHookId;
    }

    /**
     * @param int $integrationWebHookId
     */
    public function setIntegrationWebHookId($integrationWebHookId)
    {
        $this->integrationWebHookId = $integrationWebHookId;
    }

}