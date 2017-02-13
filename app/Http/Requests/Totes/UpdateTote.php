<?php

namespace App\Http\Requests\Totes;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateTote extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $barCode;

    /**
     * @var float|null
     */
    protected $weight;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->barCode                  = AU::get($data['barCode']);
        $this->weight               = AU::get($data['weight']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (!is_null($this->weight))
        {
            if (is_null(parent::getInteger($this->weight)) && is_null(parent::getFloat($this->weight)))
                throw new BadRequestHttpException('weight must be a number');

            if ($this->weight <= 0)
                throw new BadRequestHttpException('weight must be positive');
        }
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
        $object['barCode']              = $this->barCode;
        $object['weight']               = $this->weight;

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
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * @param null|string $barCode
     */
    public function setBarCode($barCode)
    {
        $this->barCode = $barCode;
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

}