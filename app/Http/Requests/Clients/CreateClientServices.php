<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateClientServices extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|int
     */
    protected $serviceIds;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->serviceIds               = AU::get($data['serviceIds']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->serviceIds))
            throw new BadRequestHttpException('serviceIds is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        $this->serviceIds               = $this->validateIds($this->serviceIds, 'serviceIds');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['serviceIds']           = $this->serviceIds;

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
     * @return int|string
     */
    public function getServiceIds()
    {
        return $this->serviceIds;
    }

    /**
     * @param int|string $serviceIds
     */
    public function setServiceIds($serviceIds)
    {
        $this->serviceIds = $serviceIds;
    }

}