<?php

namespace App\Http\Requests\Orders;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil;

class GetErrorOrders extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|int|null
     */
    protected $organizationIds;

    /**
     * @var string|int|null
     */
    protected $clientIds;

    /**
     * @var string|int|null
     */
    protected $statusIds;

    /**
     * @var bool|null
     */
    protected $addressError;

    /**
     * @var bool|null
     */
    protected $skuError;

    /**
     * @var bool
     */
    protected $isError                  = true;



    public function __construct($data = [])
    {
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->clientIds                = AU::get($data['clientIds']);
        $this->statusIds                = AU::get($data['statusIds']);
        $this->addressError             = AU::get($data['addressError']);
        $this->skuError                 = AU::get($data['skuError']);
    }

    public function validate()
    {
        $this->clientIds                = $this->validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = $this->validateIds($this->organizationIds, 'organizationIds');
        $this->statusIds                = $this->validateIds($this->clientIds, 'statusIds');
    }

    public function clean ()
    {
        $this->addressError             = BooleanUtil::getBooleanValue($this->addressError);
        $this->skuError                 = BooleanUtil::getBooleanValue($this->skuError);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['organizationIds']      = $this->organizationIds;
        $object['clientIds']            = $this->clientIds;
        $object['statusIds']            = $this->statusIds;
        $object['addressError']         = $this->addressError;
        $object['skuError']             = $this->skuError;
        $object['isError']              = $this->isError;

        return $object;
    }

    /**
     * @return int|null|string
     */
    public function getOrganizationIds()
    {
        return $this->organizationIds;
    }

    /**
     * @param int|null|string $organizationIds
     */
    public function setOrganizationIds($organizationIds)
    {
        $this->organizationIds = $organizationIds;
    }

    /**
     * @return int|null|string
     */
    public function getClientIds()
    {
        return $this->clientIds;
    }

    /**
     * @param int|null|string $clientIds
     */
    public function setClientIds($clientIds)
    {
        $this->clientIds = $clientIds;
    }

    /**
     * @return int|null|string
     */
    public function getStatusIds()
    {
        return $this->statusIds;
    }

    /**
     * @param int|null|string $statusIds
     */
    public function setStatusIds($statusIds)
    {
        $this->statusIds = $statusIds;
    }

    /**
     * @return bool|null
     */
    public function getAddressError()
    {
        return $this->addressError;
    }

    /**
     * @param bool|null $addressError
     */
    public function setAddressError($addressError)
    {
        $this->addressError = $addressError;
    }

    /**
     * @return bool|null
     */
    public function getSkuError()
    {
        return $this->skuError;
    }

    /**
     * @param bool|null $skuError
     */
    public function setSkuError($skuError)
    {
        $this->skuError = $skuError;
    }

}