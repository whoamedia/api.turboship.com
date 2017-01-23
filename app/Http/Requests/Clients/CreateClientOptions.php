<?php

namespace App\Http\Requests\Clients;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Respect\Validation\Validator as v;

class CreateClientOptions extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $defaultShipToPhone;

    /**
     * @var int
     */
    protected $defaultShipperId;

    /**
     * @var int
     */
    protected $defaultIntegratedShippingApiId;

    public function __construct($data = [])
    {
        $this->defaultShipToPhone       = AU::get($data['defaultShipToPhone']);
        $this->defaultShipperId         = AU::get($data['defaultShipperId']);
        $this->defaultIntegratedShippingApiId   = AU::get($data['defaultIntegratedShippingApiId']);
    }

    public function validate()
    {
        if (is_null($this->defaultShipperId))
            throw new BadRequestHttpException('defaultShipperId is required');

        if (is_null($this->defaultIntegratedShippingApiId))
            throw new BadRequestHttpException('defaultIntegratedShippingApiId is required');


        if (is_null(parent::getInteger($this->defaultShipperId)))
            throw new BadRequestHttpException('defaultShipperId must be integer');

        if (is_null(parent::getInteger($this->defaultIntegratedShippingApiId)))
            throw new BadRequestHttpException('defaultIntegratedShippingApiId must be integer');

        if (!is_null($this->defaultShipToPhone))
        {
            if (!v::phone()->validate($this->defaultShipToPhone))
                throw new BadRequestHttpException('Invalid phone');
        }
    }

    public function clean ()
    {
        $this->defaultShipperId         = parent::getInteger($this->defaultShipperId);
        $this->defaultIntegratedShippingApiId   = parent::getInteger($this->defaultIntegratedShippingApiId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['defaultShipToPhone']   = $this->defaultShipToPhone;
        $object['defaultShipperId']     = $this->defaultShipperId;
        $object['defaultIntegratedShippingApiId']   = $this->defaultIntegratedShippingApiId;

        return $object;
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
     * @return int
     */
    public function getDefaultShipperId()
    {
        return $this->defaultShipperId;
    }

    /**
     * @param int $defaultShipperId
     */
    public function setDefaultShipperId($defaultShipperId)
    {
        $this->defaultShipperId = $defaultShipperId;
    }

    /**
     * @return int
     */
    public function getDefaultIntegratedShippingApiId()
    {
        return $this->defaultIntegratedShippingApiId;
    }

    /**
     * @param int $defaultIntegratedShippingApiId
     */
    public function setDefaultIntegratedShippingApiId($defaultIntegratedShippingApiId)
    {
        $this->defaultIntegratedShippingApiId = $defaultIntegratedShippingApiId;
    }

}