<?php

namespace App\Http\Requests\Orders;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetOrders extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

    /**
     * @var string|null
     */
    protected $itemIds;

    /**
     * @var string|null
     */
    protected $crmSourceIds;

    /**
     * @var string|null
     */
    protected $clientIds;

    /**
     * @var string|null
     */
    protected $organizationIds;

    /**
     * @var string|null
     */
    protected $statusIds;

    /**
     * @var string|null
     */
    protected $externalIds;

    /**
     * @var string|null
     */
    protected $itemSkus;

    /**
     * @var bool|null
     */
    protected $isAddressError;

    /**
     * @var bool|null
     */
    protected $isSkuError;

    /**
     * @var bool|null
     */
    protected $isError;


    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->itemIds                  = AU::get($data['itemIds']);
        $this->crmSourceIds             = AU::get($data['crmSourceIds']);
        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->statusIds                = AU::get($data['statusIds']);
        $this->externalIds              = AU::get($data['externalIds']);
        $this->itemSkus                 = AU::get($data['itemSkus']);
        $this->isAddressError           = AU::get($data['isAddressError']);
        $this->isSkuError               = AU::get($data['isSkuError']);
        $this->isError                  = AU::get($data['isError']);
    }

    public function validate()
    {
        $this->ids                      = parent::validateIds($this->ids, 'ids');
        $this->itemIds                  = parent::validateIds($this->itemIds, 'itemIds');
        $this->clientIds                = parent::validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->statusIds                = parent::validateIds($this->statusIds, 'statusIds');
        $this->isAddressError           = parent::validateBoolean($this->isAddressError, 'isAddressError');
        $this->isSkuError               = parent::validateBoolean($this->isSkuError, 'isSkuError');
        $this->isError                  = parent::validateBoolean($this->isError, 'isError');
    }

    public function clean ()
    {

    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['clientIds']            = $this->clientIds;
        $object['organizationIds']      = $this->organizationIds;
        $object['itemIds']              = $this->itemIds;
        $object['crmSourceIds']         = $this->crmSourceIds;
        $object['statusIds']            = $this->statusIds;
        $object['externalIds']          = $this->externalIds;
        $object['itemSkus']             = $this->itemSkus;
        $object['isAddressError']       = $this->isAddressError;
        $object['isSkuError']           = $this->isSkuError;
        $object['isError']              = $this->isError;

        return $object;
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
    public function getItemIds()
    {
        return $this->itemIds;
    }

    /**
     * @param null|string $itemIds
     */
    public function setItemIds($itemIds)
    {
        $this->itemIds = $itemIds;
    }

    /**
     * @return null
     */
    public function getCrmSourceIds()
    {
        return $this->crmSourceIds;
    }

    /**
     * @param null $crmSourceIds
     */
    public function setCrmSourceIds($crmSourceIds)
    {
        $this->crmSourceIds = $crmSourceIds;
    }

    /**
     * @return null
     */
    public function getClientIds()
    {
        return $this->clientIds;
    }

    /**
     * @param null $clientIds
     */
    public function setClientIds($clientIds)
    {
        $this->clientIds = $clientIds;
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
     * @return null
     */
    public function getStatusIds()
    {
        return $this->statusIds;
    }

    /**
     * @param null $statusIds
     */
    public function setStatusIds($statusIds)
    {
        $this->statusIds = $statusIds;
    }

    /**
     * @return null
     */
    public function getExternalIds()
    {
        return $this->externalIds;
    }

    /**
     * @param null $externalIds
     */
    public function setExternalIds($externalIds)
    {
        $this->externalIds = $externalIds;
    }

    /**
     * @return null
     */
    public function getItemSkus()
    {
        return $this->itemSkus;
    }

    /**
     * @param null $itemSkus
     */
    public function setItemSkus($itemSkus)
    {
        $this->itemSkus = $itemSkus;
    }

    /**
     * @return bool|null
     */
    public function getIsAddressError()
    {
        return $this->isAddressError;
    }

    /**
     * @param bool|null $isAddressError
     */
    public function setIsAddressError($isAddressError)
    {
        $this->isAddressError = $isAddressError;
    }

    /**
     * @return bool|null
     */
    public function getIsSkuError()
    {
        return $this->isSkuError;
    }

    /**
     * @param bool|null $isSkuError
     */
    public function setIsSkuError($isSkuError)
    {
        $this->isSkuError = $isSkuError;
    }

    /**
     * @return bool|null
     */
    public function getIsError()
    {
        return $this->isError;
    }

    /**
     * @param bool|null $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
    }

}