<?php

namespace App\Http\Requests\PickInstructions;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreatePickInstruction extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $toteIds;

    /**
     * @var int|null
     */
    protected $cartId;

    /**
     * @var int|null
     */
    protected $staffId;

    /**
     * @var string|null
     */
    protected $shipmentIds;


    public function __construct($data = [])
    {
        $this->toteIds                  = AU::get($data['toteIds']);
        $this->cartId                   = AU::get($data['cartId']);
        $this->shipmentIds              = AU::get($data['shipmentIds']);
    }

    public function validate()
    {
        if (!is_null($this->toteIds))
        {
            $this->toteIds              = parent::getCommaSeparatedIds($this->toteIds);
            $this->toteIds              = parent::validateIds($this->toteIds, 'toteIds');
        }

        if (!is_null($this->cartId))
        {
            if (is_null(parent::getInteger($this->cartId)))
                throw new BadRequestHttpException('cartId is expected to be integer');
        }

        if (!is_null($this->staffId))
        {
            if (is_null(parent::getInteger($this->staffId)))
                throw new BadRequestHttpException('staffId is expected to be integer');
        }

        if (!is_null($this->shipmentIds))
        {
            $this->shipmentIds          = parent::getCommaSeparatedIds($this->shipmentIds);
            $this->shipmentIds          = parent::validateIds($this->shipmentIds, 'shipmentIds');
        }
    }

    public function clean ()
    {
        $this->cartId                   = parent::getInteger($this->cartId);
        $this->staffId                  = parent::getInteger($this->staffId);

    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['toteIds']              = $this->toteIds;
        $object['cartId']               = $this->cartId;
        $object['staffId']              = $this->staffId;
        $object['shipmentIds']          = $this->shipmentIds;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getToteIds()
    {
        return $this->toteIds;
    }

    /**
     * @param null|string $toteIds
     */
    public function setToteIds($toteIds)
    {
        $this->toteIds = $toteIds;
    }

    /**
     * @return int|null
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @param int|null $cartId
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;
    }

    /**
     * @return int|null
     */
    public function getStaffId()
    {
        return $this->staffId;
    }

    /**
     * @param int|null $staffId
     */
    public function setStaffId($staffId)
    {
        $this->staffId = $staffId;
    }

    /**
     * @return null|string
     */
    public function getShipmentIds()
    {
        return $this->shipmentIds;
    }

    /**
     * @param null|string $shipmentIds
     */
    public function setShipmentIds($shipmentIds)
    {
        $this->shipmentIds = $shipmentIds;
    }

}