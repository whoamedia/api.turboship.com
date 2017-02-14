<?php

namespace App\Http\Requests\Bins;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateBin extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $barCode;

    /**
     * @var string
     */
    protected $aisle;

    /**
     * @var string
     */
    protected $section;

    /**
     * @var string
     */
    protected $row;

    /**
     * @var string
     */
    protected $col;


    public function __construct($data = [])
    {
        $this->barCode              = AU::get($data['barCode']);
        $this->aisle                = AU::get($data['aisle']);
        $this->section              = AU::get($data['section']);
        $this->row                  = AU::get($data['row']);
        $this->col                  = AU::get($data['col']);
    }

    public function validate()
    {
        if (is_null($this->barCode))
            throw new BadRequestHttpException('barCode is required');

        if (is_null($this->aisle))
            throw new BadRequestHttpException('aisle is required');

        if (is_null($this->section))
            throw new BadRequestHttpException('section is required');

        if (is_null($this->row))
            throw new BadRequestHttpException('row is required');

        if (is_null($this->col))
            throw new BadRequestHttpException('col is required');

    }

    public function clean ()
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['barCode']          = $this->barCode;
        $object['aisle']            = $this->aisle;
        $object['section']          = $this->section;
        $object['row']              = $this->row;
        $object['col']              = $this->col;

        return $object;
    }

    /**
     * @return string
     */
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * @param string $barCode
     */
    public function setBarCode($barCode)
    {
        $this->barCode = $barCode;
    }

    /**
     * @return string
     */
    public function getAisle()
    {
        return $this->aisle;
    }

    /**
     * @param string $aisle
     */
    public function setAisle($aisle)
    {
        $this->aisle = $aisle;
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param string $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return string
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * @param string $col
     */
    public function setCol($col)
    {
        $this->col = $col;
    }

}