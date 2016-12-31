<?php

namespace App\Http\Requests\Carriers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetCarriers extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
    }

    public function validate()
    {

    }

    public function clean ()
    {
        $this->ids                      = InputUtil::getIdsString($this->ids);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param null|string $ids
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
    }

}