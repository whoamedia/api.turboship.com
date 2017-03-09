<?php

namespace App\Http\Requests\Totes;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateTote extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $barCode;

    /**
     * @var float
     */
    protected $weight;


    public function __construct($data = [])
    {
        $this->barCode              = AU::get($data['barCode']);
        $this->weight               = AU::get($data['weight']);
    }

    public function validate()
    {
        if (is_null($this->barCode))
            throw new BadRequestHttpException('barCode is required');

        if (is_null($this->weight))
            throw new BadRequestHttpException('weight is required');

        if (is_null(parent::getInteger($this->weight)) && is_null(parent::getFloat($this->weight)))
            throw new BadRequestHttpException('weight must be a number');

        if ($this->weight <= 0)
            throw new BadRequestHttpException('weight must be positive');
    }

    public function clean ()
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['barCode']          = $this->barCode;
        $object['weight']           = $this->weight;

        return $object;
    }

    /**
     * @return int
     */
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * @param int $barCode
     */
    public function setBarCode($barCode)
    {
        $this->barCode = $barCode;
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

}