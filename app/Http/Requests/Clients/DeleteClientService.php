<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeleteClientService implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $serviceId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->serviceId                = AU::get($data['serviceId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->serviceId))
            throw new BadRequestHttpException('serviceId is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->serviceId)))
            throw new BadRequestHttpException('serviceId must be integer');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->serviceId                = InputUtil::getInt($this->serviceId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['serviceId']            = $this->serviceId;

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
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * @param int $serviceId
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    }

}