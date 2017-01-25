<?php

namespace App\Http\Requests\ShippingContainers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetShippingContainers extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $shippingContainerTypeIds;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->shippingContainerTypeIds = AU::get($data['shippingContainerTypeIds']);
    }

    public function validate()
    {

    }

    public function clean ()
    {
        $this->ids                      = InputUtil::getIdsString($this->ids);
        $this->organizationIds          = InputUtil::getIdsString($this->organizationIds);
        $this->shippingContainerTypeIds = InputUtil::getIdsString($this->shippingContainerTypeIds);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['organizationIds']      = $this->organizationIds;
        $object['shippingContainerTypeIds'] = $this->shippingContainerTypeIds;

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
    public function getOrganizationIds()
    {
        return $this->organizationIds;
    }

    /**
     * @param null|string $organizationIds
     */
    public function setOrganizationIds($organizationIds)
    {
        $this->organizationIds = $organizationIds;
    }

    /**
     * @return null|string
     */
    public function getShippingContainerTypeIds()
    {
        return $this->shippingContainerTypeIds;
    }

    /**
     * @param null|string $shippingContainerTypeIds
     */
    public function setShippingContainerTypeIds($shippingContainerTypeIds)
    {
        $this->shippingContainerTypeIds = $shippingContainerTypeIds;
    }

}