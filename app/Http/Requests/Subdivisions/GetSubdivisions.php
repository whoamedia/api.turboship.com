<?php

namespace App\Http\Requests\Subdivisions;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetSubdivisions implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $countryIds;

    /**
     * @var string|null
     */
    protected $subdivisionTypeIds;

    /**
     * @var string|null
     */
    protected $names;

    /**
     * @var string
     */
    protected $symbols;

    /**
     * @var string
     */
    protected $localSymbols;

    /**
     * Defaults to 80
     * @var int
     */
    protected $limit;

    /**
     * Defaults to 1
     * @var int
     */
    protected $page;


    /**
     * @param   array|null $data
     */
    public function __construct($data = null)
    {
        if (is_array($data))
        {
            $this->ids                      = AU::get($data['ids']);
            $this->countryIds               = AU::get($data['countryIds']);
            $this->subdivisionTypeIds       = AU::get($data['subdivisionTypeIds']);
            $this->names                    = AU::get($data['names']);
            $this->symbols                  = AU::get($data['symbols']);
            $this->localSymbols             = AU::get($data['localSymbols']);
            $this->limit                    = AU::get($data['limit'], 80);
            $this->page                     = AU::get($data['page'], 1);
        }
    }

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    public function clean ()
    {
        $this->ids                          = InputUtil::getIdsString($this->ids);
        $this->countryIds                   = InputUtil::getIdsString($this->countryIds);
        $this->subdivisionTypeIds           = InputUtil::getIdsString($this->subdivisionTypeIds);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['ids']                      = $this->ids;
        $object['countryIds']               = $this->countryIds;
        $object['subdivisionTypeIds']       = $this->subdivisionTypeIds;
        $object['names']                    = $this->names;
        $object['symbols']                  = $this->symbols;
        $object['localSymbols']             = $this->localSymbols;
        $object['limit']                    = $this->limit;
        $object['page']                     = $this->page;

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
    public function getCountryIds()
    {
        return $this->countryIds;
    }

    /**
     * @param null|string $countryIds
     */
    public function setCountryIds($countryIds)
    {
        $this->countryIds = $countryIds;
    }

    /**
     * @return null|string
     */
    public function getSubdivisionTypeIds()
    {
        return $this->subdivisionTypeIds;
    }

    /**
     * @param null|string $subdivisionTypeIds
     */
    public function setSubdivisionTypeIds($subdivisionTypeIds)
    {
        $this->subdivisionTypeIds = $subdivisionTypeIds;
    }

    /**
     * @return null|string
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param null|string $names
     */
    public function setNames($names)
    {
        $this->names = $names;
    }

    /**
     * @return string
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * @param string $symbols
     */
    public function setSymbols($symbols)
    {
        $this->symbols = $symbols;
    }

    /**
     * @return string
     */
    public function getLocalSymbols()
    {
        return $this->localSymbols;
    }

    /**
     * @param string $localSymbols
     */
    public function setLocalSymbols($localSymbols)
    {
        $this->localSymbols = $localSymbols;
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
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

}