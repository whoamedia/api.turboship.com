<?php

namespace App\Http\Requests\IntegratedShippingApis;

use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetIntegratedShippingApis extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $shipperIds;

    /**
     * @var string|null
     */
    protected $organizationIds;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->shipperIds               = AU::get($data['shipperIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->shipperIds               = parent::validateIds($this->shipperIds, 'shipperIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['shipperIds']           = $this->shipperIds;
        $object['organizationIds']      = $this->organizationIds;

        return $object;
    }

    public function clean()
    {
        $this->ids                      = parent::getCommaSeparatedIds($this->ids);
        $this->shipperIds               = parent::getCommaSeparatedIds($this->shipperIds);
        $this->organizationIds          = parent::getCommaSeparatedIds($this->organizationIds);
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
    public function getShipperIds()
    {
        return $this->shipperIds;
    }

    /**
     * @param null|string $shipperIds
     */
    public function setShipperIds($shipperIds)
    {
        $this->shipperIds = $shipperIds;
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

}