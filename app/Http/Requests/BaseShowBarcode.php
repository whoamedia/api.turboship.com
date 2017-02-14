<?php

namespace App\Http\Requests;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseShowBarcode extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $barCode;


    public function __construct($data = [])
    {
        $this->barCode                  = AU::get($data['barCode']);
    }

    public function validate ()
    {
        if (is_null($this->barCode))
            throw new BadRequestHttpException('barCode is required');

        if (empty(trim($this->barCode)))
            throw new BadRequestHttpException('barCode cannot be empty');
    }

    public function clean ()
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['barCode']              = $this->barCode;
        return $object;
    }

    /**
     * @return string
     */
    public function getBarCode ()
    {
        return $this->barCode;
    }

    /**
     * @param string $barCode
     */
    public function setBarCode ($barCode)
    {
        $this->barCode = $barCode;
    }

}