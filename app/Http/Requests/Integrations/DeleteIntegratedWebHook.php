<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeleteIntegratedWebHook implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $integratedWebHookId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->integratedWebHookId      = AU::get($data['integratedWebHookId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->integratedWebHookId))
            throw new BadRequestHttpException('integratedWebHookId is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->integratedWebHookId)))
            throw new BadRequestHttpException('integratedWebHookId must be integer');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->integratedWebHookId      = InputUtil::getInt($this->integratedWebHookId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['integratedWebHookId']  = $this->integratedWebHookId;

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
    public function getIntegratedWebHookId()
    {
        return $this->integratedWebHookId;
    }

    /**
     * @param int $integratedWebHookId
     */
    public function setIntegratedWebHookId($integratedWebHookId)
    {
        $this->integratedWebHookId = $integratedWebHookId;
    }

}