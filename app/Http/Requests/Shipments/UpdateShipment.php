<?php

namespace App\Http\Requests\Shipments;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateShipment extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $shippingContainerId;

    /**
     * @var float|null
     */
    protected $weight;

    /**
     * @var int|null
     */
    protected $serviceId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->shippingContainerId      = AU::get($data['shippingContainerId']);
        $this->weight                   = AU::get($data['weight']);
        $this->serviceId                = AU::get($data['serviceId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (!is_null($this->shippingContainerId))
        {
            if (is_null(InputUtil::getInt($this->shippingContainerId)))
                throw new BadRequestHttpException('shippingContainerId must be integer');
        }

        if (!is_null($this->weight))
        {
            if (is_null(InputUtil::getFloat($this->weight)))
                throw new BadRequestHttpException('weight must be float');
        }

        if (!is_null($this->serviceId))
        {
            if (is_null(InputUtil::getInt($this->serviceId)))
                throw new BadRequestHttpException('serviceId must be integer');
        }

    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->shippingContainerId      = InputUtil::getInt($this->shippingContainerId);
        $this->weight                   = InputUtil::getFloat($this->weight);
        $this->serviceId                = InputUtil::getInt($this->serviceId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['shippingContainerId']  = $this->shippingContainerId;
        $object['weight']               = $this->weight;
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
     * @return int|null
     */
    public function getShippingContainerId()
    {
        return $this->shippingContainerId;
    }

    /**
     * @param int|null $shippingContainerId
     */
    public function setShippingContainerId($shippingContainerId)
    {
        $this->shippingContainerId = $shippingContainerId;
    }

    /**
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return int|null
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * @param int|null $serviceId
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    }

}