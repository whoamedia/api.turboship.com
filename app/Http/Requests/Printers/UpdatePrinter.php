<?php

namespace App\Http\Requests\Printers;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdatePrinter extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $ipAddress;

    /**
     * @var int|null
     */
    protected $printerTypeId;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
        $this->ipAddress                = AU::get($data['ipAddress']);
        $this->printerTypeId            = AU::get($data['printerTypeId']);
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
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['description']          = $this->description;
        $object['ipAddress']            = $this->ipAddress;
        $object['printerTypeId']        = $this->printerTypeId;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param null|string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return int|null
     */
    public function getPrinterTypeId()
    {
        return $this->printerTypeId;
    }

    /**
     * @param int|null $printerTypeId
     */
    public function setPrinterTypeId($printerTypeId)
    {
        $this->printerTypeId = $printerTypeId;
    }

}