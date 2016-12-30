<?php

namespace App\Http\Requests\Shipments;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PurchasePostage extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $rateId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->rateId                   = AU::get($data['rateId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->rateId))
            throw new BadRequestHttpException('rateId is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->rateId)))
            throw new BadRequestHttpException('rateId must be integer');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->rateId                   = InputUtil::getInt($this->rateId);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['rateId']               = $this->rateId;

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
    public function getRateId()
    {
        return $this->rateId;
    }

    /**
     * @param int $rateId
     */
    public function setRateId($rateId)
    {
        $this->rateId = $rateId;
    }

}