<?php

namespace App\Http\Requests\Bins;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateBin extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $barCode;

    /**
     * @var string|null
     */
    protected $aisle;

    /**
     * @var string|null
     */
    protected $section;

    /**
     * @var string|null
     */
    protected $row;

    /**
     * @var string|null
     */
    protected $col;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->barCode                  = AU::get($data['barCode']);
        $this->aisle                    = AU::get($data['aisle']);
        $this->section                  = AU::get($data['section']);
        $this->row                      = AU::get($data['row']);
        $this->col                      = AU::get($data['col']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['barCode']              = $this->barCode;
        $object['aisle']                = $this->aisle;
        $object['section']              = $this->section;
        $object['row']                  = $this->row;
        $object['col']                  = $this->col;

        return $object;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * @param null|string $barCode
     */
    public function setBarCode($barCode)
    {
        $this->barCode = $barCode;
    }

    /**
     * @return null|string
     */
    public function getAisle()
    {
        return $this->aisle;
    }

    /**
     * @param null|string $aisle
     */
    public function setAisle($aisle)
    {
        $this->aisle = $aisle;
    }

    /**
     * @return null|string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param null|string $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return null|string
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param null|string $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return null|string
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * @param null|string $col
     */
    public function setCol($col)
    {
        $this->col = $col;
    }

}