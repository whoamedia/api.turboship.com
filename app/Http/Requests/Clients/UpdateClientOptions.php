<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Respect\Validation\Validator as v;

class UpdateClientOptions extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $defaultShipToPhone;

    /**
     * @var int|null
     */
    protected $defaultShipperId;

    /**
     * @var int|null
     */
    protected $defaultIntegratedShippingApiId;

    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->defaultShipToPhone       = AU::get($data['defaultShipToPhone']);
        $this->defaultShipperId         = AU::get($data['defaultShipperId']);
        $this->defaultIntegratedShippingApiId   = AU::get($data['defaultIntegratedShippingApiId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(parent::getInteger($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (!is_null($this->defaultShipperId))
        {
            if (is_null(parent::getInteger($this->defaultShipperId)))
                throw new BadRequestHttpException('defaultShipperId must be integer');
        }

        if (!is_null($this->defaultIntegratedShippingApiId))
        {
            if (is_null(parent::getInteger($this->defaultIntegratedShippingApiId)))
                throw new BadRequestHttpException('defaultIntegratedShippingApiId must be integer');
        }

        if (!is_null($this->defaultShipToPhone))
        {
            if (!v::phone()->validate($this->defaultShipToPhone))
                throw new BadRequestHttpException('Invalid phone');
        }
    }

    public function clean ()
    {
        $this->id                       = parent::getInteger($this->id);
        $this->defaultShipperId         = parent::getInteger($this->defaultShipperId);
        $this->defaultIntegratedShippingApiId   = parent::getInteger($this->defaultIntegratedShippingApiId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['defaultShipToPhone']   = $this->defaultShipToPhone;
        $object['defaultShipperId']     = $this->defaultShipperId;
        $object['defaultIntegratedShippingApiId']   = $this->defaultIntegratedShippingApiId;

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
     * @return null|string
     */
    public function getDefaultShipToPhone()
    {
        return $this->defaultShipToPhone;
    }

    /**
     * @param null|string $defaultShipToPhone
     */
    public function setDefaultShipToPhone($defaultShipToPhone)
    {
        $this->defaultShipToPhone = $defaultShipToPhone;
    }

    /**
     * @return int|null
     */
    public function getDefaultShipperId()
    {
        return $this->defaultShipperId;
    }

    /**
     * @param int|null $defaultShipperId
     */
    public function setDefaultShipperId($defaultShipperId)
    {
        $this->defaultShipperId = $defaultShipperId;
    }

    /**
     * @return int|null
     */
    public function getDefaultIntegratedShippingApiId()
    {
        return $this->defaultIntegratedShippingApiId;
    }

    /**
     * @param int|null $defaultIntegratedShippingApiId
     */
    public function setDefaultIntegratedShippingApiId($defaultIntegratedShippingApiId)
    {
        $this->defaultIntegratedShippingApiId = $defaultIntegratedShippingApiId;
    }

}