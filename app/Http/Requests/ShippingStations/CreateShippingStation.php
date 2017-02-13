<?php

namespace App\Http\Requests\ShippingStations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateShippingStation extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $printerId;

    /**
     * @var int
     */
    protected $organizationId;


    public function __construct($data = [])
    {
        $this->name                     = AU::get($data['name']);
        $this->organizationId           = AU::get($data['organizationId']);
        $this->printerId                = AU::get($data['printerId']);
    }

    public function validate()
    {
        if (is_null($this->name))
            throw new BadRequestHttpException('name is required');
        else if (empty(trim($this->name)))
            throw new BadRequestHttpException('name cannot be empty');

        if (is_null($this->organizationId))
            throw new BadRequestHttpException('organizationId is required');
        else if (is_null(parent::getInteger($this->organizationId)))
            throw new BadRequestHttpException('organizationId expected to be integer');

        if (is_null($this->printerId))
            throw new BadRequestHttpException('printerId is required');
        else if (is_null(parent::getInteger($this->printerId)))
            throw new BadRequestHttpException('printerId expected to be integer');

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
        $object['organizationId']       = $this->organizationId;
        $object['printerId']            = $this->printerId;

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
     * @return int
     */
    public function getPrinterId()
    {
        return $this->printerId;
    }

    /**
     * @param int $printerId
     */
    public function setPrinterId($printerId)
    {
        $this->printerId = $printerId;
    }

    /**
     * @return int
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param int $organizationId
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
    }

}