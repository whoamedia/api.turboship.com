<?php

namespace App\Http\Requests\Bins;

use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetBins extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
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
    protected $barCodes;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $page;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->barCodes                 = AU::get($data['barCodes']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->limit                    = AU::get($data['limit'], 80);
        $this->page                     = AU::get($data['page'], 1);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['barCodes']             = $this->barCodes;
        $object['organizationIds']      = $this->organizationIds;
        $object['limit']                = $this->limit;
        $object['page']                 = $this->page;

        return $object;
    }

    public function clean()
    {
        $this->ids                      = parent::getCommaSeparatedIds($this->ids);
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