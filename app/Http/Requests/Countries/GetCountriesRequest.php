<?php

namespace App\Http\Requests\Countries;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;

class GetCountriesRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $names;

    /**
     * @var string|null
     */
    protected $iso2s;

    /**
     * @var string|null
     */
    protected $iso3s;

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
     * @param   array $data
     */
    public function __construct($data = [])
    {
        if (is_array($data))
        {
            $this->ids                      = AU::get($data['ids']);
            $this->names                    = AU::get($data['names']);
            $this->iso2s                    = AU::get($data['iso2s']);
            $this->iso3s                    = AU::get($data['iso3s']);
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
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['ids']                      = $this->ids;
        $object['names']                    = $this->names;
        $object['iso2s']                    = $this->iso2s;
        $object['iso3s']                    = $this->iso3s;
        $object['limit']                    = $this->limit;
        $object['page']                     = $this->page;

        return $object;
    }


    /**
     * @return int|string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param int|string $ids
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return string
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param string $names
     */
    public function setNames($names)
    {
        $this->names = $names;
    }

    /**
     * @return string
     */
    public function getIso2s()
    {
        return $this->iso2s;
    }

    /**
     * @param string $iso2s
     */
    public function setIso2s($iso2s)
    {
        $this->iso2s = $iso2s;
    }

    /**
     * @return string
     */
    public function getIso3s()
    {
        return $this->iso3s;
    }

    /**
     * @param string $iso3s
     */
    public function setIso3s($iso3s)
    {
        $this->iso3s = $iso3s;
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