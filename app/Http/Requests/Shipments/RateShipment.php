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
    protected $shippingContainerId;

    /**
     * @var float
     */
    protected $weight;

    /**
     * @var int
     */
    protected $integratedShippingApiId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->shippingContainerId      = AU::get($data['shippingContainerId']);
        $this->weight                   = AU::get($data['weight']);
        $this->integratedShippingApiId  = AU::get($data['integratedShippingApiId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->shippingContainerId))
            throw new BadRequestHttpException('shippingContainerId is required');

        if (is_null($this->weight))
            throw new BadRequestHttpException('weight is required');

        if (is_null($this->integratedShippingApiId))
            throw new BadRequestHttpException('integratedShippingApiId is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->shippingContainerId)))
            throw new BadRequestHttpException('shippingContainerId must be integer');

        if (is_null(InputUtil::getFloat($this->weight)))
            throw new BadRequestHttpException('weight must be float');

        if (is_null(InputUtil::getInt($this->integratedShippingApiId)))
            throw new BadRequestHttpException('integratedShippingApiId must be integer');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->shippingContainerId      = InputUtil::getInt($this->shippingContainerId);
        $this->weight                   = InputUtil::getFloat($this->weight);
        $this->integratedShippingApiId  = InputUtil::getInt($this->integratedShippingApiId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['shippingContainerId']  = $this->shippingContainerId;
        $object['weight']               = $this->weight;
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
    public function getShippingContainerId()
    {
        return $this->shippingContainerId;
    }

    /**
     * @param int $shippingContainerId
     */
    public function setShippingContainerId($shippingContainerId)
    {
        $this->shippingContainerId = $shippingContainerId;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
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