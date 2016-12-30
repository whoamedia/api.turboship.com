<?php

namespace App\Http\Requests\Shipments;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RateShipment extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $integratedShippingApiId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->integratedShippingApiId  = AU::get($data['integratedShippingApiId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->integratedShippingApiId))
            throw new BadRequestHttpException('integratedShippingApiId is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->integratedShippingApiId)))
            throw new BadRequestHttpException('integratedShippingApiId must be integer');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->integratedShippingApiId  = InputUtil::getInt($this->integratedShippingApiId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['integratedShippingApiId']  = $this->integratedShippingApiId;

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
    public function getIntegratedShippingApiId()
    {
        return $this->integratedShippingApiId;
    }

    /**
     * @param int $integratedShippingApiId
     */
    public function setIntegratedShippingApiId($integratedShippingApiId)
    {
        $this->integratedShippingApiId = $integratedShippingApiId;
    }

}