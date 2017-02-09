<?php

namespace App\Http\Requests\Bins;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateBin extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $barCode;


    public function __construct($data = [])
    {
        $this->barCode              = AU::get($data['barCode']);
    }

    public function validate()
    {
        if (is_null($this->barCode))
            throw new BadRequestHttpException('barCode is required');
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

}