<?php

namespace App\Http\Requests\Shipments;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateShipmentsJob extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $clientId;

    /**
     * @var int
     */
    protected $shipperId;


    public function __construct($data = [])
    {
        $this->clientId                 = AU::get($data['clientId']);
        $this->shipperId                = AU::get($data['shipperId']);
    }

    public function validate()
    {
        if (is_null($this->clientId))
            throw new BadRequestHttpException('clientId is required');

        if (is_null($this->shipperId))
            throw new BadRequestHttpException('shipperId is required');


        if (is_null(InputUtil::getInt($this->clientId)))
            throw new BadRequestHttpException('clientId must be integer');

        if (is_null(InputUtil::getInt($this->shipperId)))
            throw new BadRequestHttpException('shipperId must be integer');
    }

    public function clean ()
    {
        $this->clientId                 = InputUtil::getInt($this->clientId);
        $this->shipperId                = InputUtil::getInt($this->shipperId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['clientId']             = $this->clientId;
        $object['shipperId']            = $this->shipperId;

        return $object;
    }

    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return int
     */
    public function getShipperId()
    {
        return $this->shipperId;
    }

    /**
     * @param int $shipperId
     */
    public function setShipperId($shipperId)
    {
        $this->shipperId = $shipperId;
    }

}