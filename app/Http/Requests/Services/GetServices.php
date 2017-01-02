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

    /**
     * @var bool|null
     */
    protected $isDomestic;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->carrierIds               = AU::get($data['carrierIds']);
        $this->isDomestic               = AU::get($data['isDomestic']);
    }

    public function validate()
    {
        $this->isDomestic               = parent::validateBoolean($this->isDomestic, 'isDomestic');
    }

    public function clean ()
    {
        $this->ids                      = InputUtil::getIdsString($this->ids);
        $this->carrierIds               = InputUtil::getIdsString($this->carrierIds);
        $this->isDomestic               = parent::getBoolean($this->isDomestic);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['carrierIds']           = $this->carrierIds;
        $object['isDomestic']           = $this->isDomestic;

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

    /**
     * @return bool|null
     */
    public function getIsDomestic()
    {
        return $this->isDomestic;
    }

    /**
     * @param bool|null $isDomestic
     */
    public function setIsDomestic($isDomestic)
    {
        $this->isDomestic = $isDomestic;
    }

}