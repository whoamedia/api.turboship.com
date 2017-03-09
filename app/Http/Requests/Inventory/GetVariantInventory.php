<?php

namespace App\Http\Requests\Inventory;


use App\Http\Requests\BaseGet;
use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetVariantInventory extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $clientIds;

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $inventoryLocationIds;

    /**
     * @var string|null
     */
    protected $variantIds;

    /**
     * @var string|null
     */
    protected $productIds;

    /**
     * @var bool
     */
    protected $groupedReport;

    /**
     * @var bool
     */
    protected $inventoryLocationReport;


    public function __construct($data = [])
    {
        parent::__construct('variantInventory.id', $data);

        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->inventoryLocationIds     = AU::get($data['inventoryLocationIds']);
        $this->variantIds               = AU::get($data['variantIds']);
        $this->productIds               = AU::get($data['productIds']);
        $this->groupedReport            = AU::get($data['groupedReport'], false);
        $this->inventoryLocationReport  = AU::get($data['inventoryLocationReport'], false);
    }


    public function validate()
    {
        parent::validate();

        $this->clientIds                = parent::validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->inventoryLocationIds     = parent::validateIds($this->inventoryLocationIds, 'inventoryLocationIds');
        $this->variantIds               = parent::validateIds($this->variantIds, 'variantIds');
        $this->productIds               = parent::validateIds($this->productIds, 'productIds');
        $this->groupedReport            = parent::validateBoolean($this->groupedReport, 'groupedReport');
        $this->inventoryLocationReport  = parent::validateBoolean($this->inventoryLocationReport, 'inventoryLocationReport');
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();

        $object['clientIds']            = $this->clientIds;
        $object['organizationIds']      = $this->organizationIds;
        $object['inventoryLocationIds'] = $this->inventoryLocationIds;
        $object['variantIds']           = $this->variantIds;
        $object['productIds']           = $this->productIds;
        $object['groupedReport']        = $this->groupedReport;
        $object['inventoryLocationReport'] = $this->inventoryLocationReport;

        return $object;
    }


    /**
     * @return null|string
     */
    public function getClientIds()
    {
        return $this->clientIds;
    }

    /**
     * @param null|string $clientIds
     */
    public function setClientIds($clientIds)
    {
        $this->clientIds = $clientIds;
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

    /**
     * @return null|string
     */
    public function getVariantIds()
    {
        return $this->variantIds;
    }

    /**
     * @param null|string $variantIds
     */
    public function setVariantIds($variantIds)
    {
        $this->variantIds = $variantIds;
    }

    /**
     * @return null|string
     */
    public function getProductIds()
    {
        return $this->productIds;
    }

    /**
     * @param null|string $productIds
     */
    public function setProductIds($productIds)
    {
        $this->productIds = $productIds;
    }

    /**
     * @return bool
     */
    public function getGroupedReport()
    {
        return $this->groupedReport;
    }

    /**
     * @param bool $groupedReport
     */
    public function setGroupedReport($groupedReport)
    {
        $this->groupedReport = $groupedReport;
    }

    /**
     * @return bool
     */
    public function getInventoryLocationReport()
    {
        return $this->inventoryLocationReport;
    }

    /**
     * @param bool $inventoryLocationReport
     */
    public function setInventoryLocationReport($inventoryLocationReport)
    {
        $this->inventoryLocationReport = $inventoryLocationReport;
    }

}