<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeleteClientIntegrationWebHook implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $clientWebHookId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->clientWebHookId          = AU::get($data['clientWebHookId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->clientWebHookId))
            throw new BadRequestHttpException('clientWebHookId is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->clientWebHookId)))
            throw new BadRequestHttpException('clientWebHookId must be integer');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->clientWebHookId          = InputUtil::getInt($this->clientWebHookId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['clientWebHookId']      = $this->clientWebHookId;

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
     * @return int
     */
    public function getClientWebHookId()
    {
        return $this->clientWebHookId;
    }

    /**
     * @param int $clientWebHookId
     */
    public function setClientWebHookId($clientWebHookId)
    {
        $this->clientWebHookId = $clientWebHookId;
    }

}