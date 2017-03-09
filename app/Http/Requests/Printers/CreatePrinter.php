<?php

namespace App\Http\Requests\Printers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class CreatePrinter extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $printerTypeId;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
        $this->printerTypeId            = AU::get($data['printerTypeId']);
    }

    public function validate()
    {
        if (is_null($this->name))
            throw new BadRequestHttpException('name is required');

        if (is_null($this->description))
            throw new BadRequestHttpException('description is required');

        if (is_null($this->printerTypeId))
            throw new BadRequestHttpException('printerTypeId is required');


        if (empty(trim($this->name)))
            throw new BadRequestHttpException('name cannot be empty');

        if (empty(trim($this->description)))
            throw new BadRequestHttpException('description cannot be empty');

        $this->printerTypeId            = parent::getInteger($this->printerTypeId);
        if (is_null($this->printerTypeId))
            throw new BadRequestHttpException('printerTypeId must be integer');
    }

    public function clean ()
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['name']                 = $this->name;
        $object['description']          = $this->description;
        $object['printerTypeId']        = $this->printerTypeId;

        return $object;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPrinterTypeId()
    {
        return $this->printerTypeId;
    }

    /**
     * @param int $printerTypeId
     */
    public function setPrinterTypeId($printerTypeId)
    {
        $this->printerTypeId = $printerTypeId;
    }

}