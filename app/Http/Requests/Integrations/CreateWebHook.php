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
    protected $id;

    /**
     * @var string
     */
    protected $integrationWebHookIds;

    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->integrationWebHookIds    = AU::get($data['integrationWebHookIds']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->integrationWebHookIds))
            throw new BadRequestHttpException('integrationWebHookIds is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        $integrationWebHookIds          = explode(',', $this->integrationWebHookIds);
        foreach ($integrationWebHookIds AS $id)
        {
            if (is_null(InputUtil::getInt($id)))
                throw new BadRequestHttpException('integrationWebHookId must be integer');
        }
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);

        $ids                            = '';
        $integrationWebHookIds          = explode(',', $this->integrationWebHookIds);
        foreach ($integrationWebHookIds AS $item)
        {
            $ids                        .= $item . ',';
        }
        $this->integrationWebHookIds    = rtrim($ids, ',');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['integrationWebHookIds']= $this->integrationWebHookIds;

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
    public function getIntegrationWebHookIds()
    {
        return $this->integrationWebHookIds;
    }

    /**
     * @param string $integrationWebHookIds
     */
    public function setIntegrationWebHookIds($integrationWebHookIds)
    {
        $this->integrationWebHookIds = $integrationWebHookIds;
    }

}