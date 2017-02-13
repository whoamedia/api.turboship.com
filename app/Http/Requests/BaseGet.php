<?php

namespace App\Http\Requests;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;

abstract class BaseGet extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $direction;


    public function __construct($defaultOrderBy, $data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->limit                    = AU::get($data['limit'], 80);
        $this->orderBy                  = AU::get($data['orderBy'], $defaultOrderBy);
        $this->direction                = AU::get($data['direction'], 'ASC');
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->direction                = parent::validateOrderByDirection($this->direction);
    }

    public function clean ()
    {

    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['limit']                = $this->limit;
        $object['orderBy']              = $this->orderBy;
        $object['direction']            = $this->direction;

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

}