<?php

namespace App\Http\Requests\ShippingStations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateShippingStation extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
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
     * @var int|null
     */
    protected $printerId;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->printerId                = AU::get($data['printerId']);
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null(parent::getInteger($this->id)))
            throw new BadRequestHttpException('id expected to be integer');

        if (is_null($this->printerId))
        {
            if (is_null(parent::getInteger($this->printerId)))
                throw new BadRequestHttpException('printerId expected to be integer');
        }

    }

    public function clean ()
    {
        $this->id                       = parent::getInteger($this->id);
        $this->printerId                = parent::getInteger($this->printerId);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['name']                 = $this->name;
        $object['printerId']            = $this->printerId;

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
     * @return int|null
     */
    public function getPrinterId()
    {
        return $this->printerId;
    }

    /**
     * @param int|null $printerId
     */
    public function setPrinterId($printerId)
    {
        $this->printerId = $printerId;
    }

}