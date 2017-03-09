<?php

namespace App\Http\Requests\Bins;

use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseGet;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetBins extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $barCodes;

    /**
     * @var string|null
     */
    protected $aisles;

    /**
     * @var string|null
     */
    protected $sections;

    /**
     * @var string|null
     */
    protected $rows;

    /**
     * @var string|null
     */
    protected $cols;


    public function __construct($data = [])
    {
        parent::__construct('bin.id', $data);

        $this->barCodes                 = AU::get($data['barCodes']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->aisles                   = AU::get($data['aisles']);
        $this->sections                 = AU::get($data['sections']);
        $this->rows                     = AU::get($data['rows']);
        $this->cols                     = AU::get($data['cols']);
    }

    public function validate()
    {
        parent::validate();

        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['barCodes']             = $this->barCodes;
        $object['organizationIds']      = $this->organizationIds;
        $object['aisles']               = $this->aisles;
        $object['sections']             = $this->sections;
        $object['rows']                 = $this->rows;
        $object['cols']                 = $this->cols;

        return $object;
    }

    public function clean()
    {
        parent::clean();
        $this->organizationIds          = parent::getCommaSeparatedIds($this->organizationIds);
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
     * @return null|string
     */
    public function getAisles()
    {
        return $this->aisles;
    }

    /**
     * @param null|string $aisles
     */
    public function setAisles($aisles)
    {
        $this->aisles = $aisles;
    }

    /**
     * @return null|string
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param null|string $sections
     */
    public function setSections($sections)
    {
        $this->sections = $sections;
    }

    /**
     * @return null|string
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param null|string $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @return null|string
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @param null|string $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
    }

}