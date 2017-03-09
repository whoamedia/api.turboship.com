<?php

namespace App\Http\Requests\Inventory;


use App\Http\Requests\BaseGet;
use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetInventory extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $inventoryLocationIds;


    public function __construct($data = [])
    {
        parent::__construct('inventory.id', $data);

        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->inventoryLocationIds     = AU::get($data['inventoryLocationIds']);
    }


    public function validate()
    {
        parent::validate();

        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->inventoryLocationIds     = parent::validateIds($this->inventoryLocationIds, 'inventoryLocationIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['organizationIds']      = $this->organizationIds;
        $object['inventoryLocationIds'] = $this->inventoryLocationIds;

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
    public function getInventoryLocationIds()
    {
        return $this->inventoryLocationIds;
    }

    /**
     * @param null|string $inventoryLocationIds
     */
    public function setInventoryLocationIds($inventoryLocationIds)
    {
        $this->inventoryLocationIds = $inventoryLocationIds;
    }

}