<?php

namespace App\Http\Requests\ShippingContainers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseGet;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetShippingContainers extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

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
        parent::__construct('shippingContainer.id', $data);

        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->shippingContainerTypeIds = AU::get($data['shippingContainerTypeIds']);
    }

    public function validate()
    {
        parent::validate();

        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->shippingContainerTypeIds = parent::validateIds($this->shippingContainerTypeIds, 'shippingContainerTypeIds');
    }

    public function clean ()
    {
        parent::clean();

        $this->organizationIds          = InputUtil::getIdsString($this->organizationIds);
        $this->shippingContainerTypeIds = InputUtil::getIdsString($this->shippingContainerTypeIds);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['organizationIds']      = $this->organizationIds;
        $object['shippingContainerTypeIds'] = $this->shippingContainerTypeIds;

        return $object;
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