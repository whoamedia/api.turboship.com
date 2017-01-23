<?php

namespace App\Http\Requests\Products;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetProducts extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $ids;

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
    protected $aliasIds;

    /**
     * @var string|null
     */
    protected $variantIds;

    /**
     * @var string|null
     */
    protected $variantSkus;

    /**
     * @var string|null
     */
    protected $externalIds;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $direction;

    public function __construct($data = [])
    {
        $this->ids                      = AU::get($data['ids']);
        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->aliasIds                 = AU::get($data['aliasIds']);
        $this->variantIds               = AU::get($data['variantIds']);
        $this->variantSkus              = AU::get($data['variantSkus']);
        $this->externalIds              = AU::get($data['externalIds']);
        $this->limit                    = AU::get($data['limit'], 80);
        $this->orderBy                  = AU::get($data['orderBy'], 'product.id');
        $this->direction                = AU::get($data['direction'], 'ASC');
    }

    public function validate()
    {
        $this->ids                      = $this->validateIds($this->ids, 'ids');
        $this->clientIds                = $this->validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = $this->validateIds($this->organizationIds, 'organizationIds');
        $this->aliasIds                 = $this->validateIds($this->aliasIds, 'aliasIds');
        $this->variantIds               = $this->validateIds($this->variantIds, 'variantIds');
        $this->direction                = parent::validateOrderByDirection($this->direction);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['ids']                  = $this->ids;
        $object['clientIds']            = $this->clientIds;
        $object['organizationIds']      = $this->organizationIds;
        $object['aliasIds']             = $this->aliasIds;
        $object['variantIds']           = $this->variantIds;
        $object['variantSkus']          = $this->variantSkus;
        $object['externalIds']          = $this->externalIds;
        $object['limit']                = $this->limit;
        $object['orderBy']              = $this->orderBy;
        $object['direction']            = $this->direction;

        return $object;
    }

    public function clean ()
    {

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
    public function getAliasIds()
    {
        return $this->aliasIds;
    }

    /**
     * @param null|string $aliasIds
     */
    public function setAliasIds($aliasIds)
    {
        $this->aliasIds = $aliasIds;
    }

    /**
     * @return null|string
     */
    public function getClientIds()
    {
        return $this->clientIds;
    }

    /**
     * @param null|string $clientIds
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
     * @return null|string
     */
    public function getVariantIds()
    {
        return $this->variantIds;
    }

    /**
     * @param null|string $variantIds
     */
    public function setVariantIds($variantIds)
    {
        $this->variantIds = $variantIds;
    }

    /**
     * @return null|string
     */
    public function getVariantSkus()
    {
        return $this->variantSkus;
    }

    /**
     * @param null|string $variantSkus
     */
    public function setVariantSkus($variantSkus)
    {
        $this->variantSkus = $variantSkus;
    }

    /**
     * @return null|string
     */
    public function getExternalIds()
    {
        return $this->externalIds;
    }

    /**
     * @param null|string $externalIds
     */
    public function setExternalIds($externalIds)
    {
        $this->externalIds = $externalIds;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

}