<?php

namespace App\Http\Requests\Inventory;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetInventory extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $inventoryLocationIds;

    /**
     * @var string|null
     */
    protected $barCodes;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $direction;

    /**
     * @var int
     */
    protected $limit;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->inventoryLocationIds     = AU::get($data['inventoryLocationIds']);
        $this->barCodes                 = AU::get($data['barCodes']);
        $this->orderBy                  = AU::get($data['orderBy'], 'postage.id');
        $this->direction                = AU::get($data['direction'], 'ASC');
        $this->limit                    = AU::get($data['limit'], 80);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->inventoryLocationIds     = parent::validateIds($this->inventoryLocationIds, 'inventoryLocationIds');
        $this->direction                = parent::validateOrderByDirection($this->direction);
    }

    public function clean()
    {

    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['inventoryLocationIds'] = $this->inventoryLocationIds;
        $object['barCodes']             = $this->barCodes;
        $object['orderBy']              = $this->orderBy;
        $object['direction']            = $this->direction;
        $object['limit']                = $this->limit;

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
    public function getBarCodes()
    {
        return $this->barCodes;
    }

    /**
     * @param null|string $barCodes
     */
    public function setBarCodes($barCodes)
    {
        $this->barCodes = $barCodes;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

}