<?php

namespace App\Http\Requests\Inventory;


use App\Http\Requests\BaseRequest;
use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateInventory extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string
     */
    protected $barCode;

    /**
     * @var int
     */
    protected $organizationId;

    /**
     * @var int
     */
    protected $binId;


    public function __construct($data = [])
    {
        $this->barCode                  = AU::get($data['barCode']);
        $this->organizationId           = AU::get($data['organizationId']);
        $this->binId                    = AU::get($data['binId']);
    }

    public function validate()
    {
        if (is_null($this->barCode))
            throw new BadRequestHttpException('barCode is required');

        if (empty(trim($this->barCode)))
            throw new BadRequestHttpException('barCode cannot be empty');


        if (is_null($this->organizationId))
            throw new BadRequestHttpException('organizationId is required');

        if (is_null(parent::getInteger($this->organizationId)))
            throw new BadRequestHttpException('organizationId must be integer');


        if (is_null($this->binId))
            throw new BadRequestHttpException('binId is required');

        if (is_null(parent::getInteger($this->binId)))
            throw new BadRequestHttpException('binId must be integer');
    }

    public function clean()
    {
        $this->organizationId           = parent::getInteger($this->organizationId);
        $this->binId                    = parent::getInteger($this->binId);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['barCode']              = $this->barCode;
        $object['organizationId']       = $this->organizationId;
        $object['binId']                = $this->binId;

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

    /**
     * @return int
     */
    public function getBinId()
    {
        return $this->binId;
    }

    /**
     * @param int $binId
     */
    public function setBinId($binId)
    {
        $this->binId = $binId;
    }

}