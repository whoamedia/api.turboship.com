<?php

namespace App\Http\Requests\Services;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetServices extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $carrierIds;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->carrierIds               = AU::get($data['carrierIds']);
    }

    public function validate()
    {

    }

    public function clean ()
    {
        $this->ids                      = InputUtil::getIdsString($this->ids);
        $this->carrierIds               = InputUtil::getIdsString($this->carrierIds);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['carrierIds']           = $this->carrierIds;

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

    /**
     * @return null|string
     */
    public function getCarrierIds()
    {
        return $this->carrierIds;
    }

    /**
     * @param null|string $carrierIds
     */
    public function setCarrierIds($carrierIds)
    {
        $this->carrierIds = $carrierIds;
    }

}