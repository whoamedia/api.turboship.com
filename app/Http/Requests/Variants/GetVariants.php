<?php

namespace App\Http\Requests\Variants;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseGet;
use jamesvweston\Utilities\ArrayUtil AS AU;

class GetVariants extends BaseGet implements Cleanable, Validatable, \JsonSerializable
{

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
    protected $productIds;

    /**
     * @var string|null
     */
    protected $sourceIds;

    /**
     * @var string|null
     */
    protected $externalIds;

    /**
     * @var string|null
     */
    protected $skus;

    /**
     * @var string|null
     */
    protected $barCodes;


    public function __construct($data = [])
    {
        parent::__construct('variant.id', $data);

        $this->clientIds                = AU::get($data['clientIds']);
        $this->organizationIds          = AU::get($data['organizationIds']);
        $this->productIds               = AU::get($data['productIds']);
        $this->sourceIds                = AU::get($data['sourceIds']);
        $this->externalIds              = AU::get($data['externalIds']);
        $this->skus                     = AU::get($data['skus']);
        $this->barCodes                 = AU::get($data['barCodes']);
    }

    public function validate()
    {
        parent::validate();

        $this->clientIds                = parent::validateIds($this->clientIds, 'clientIds');
        $this->organizationIds          = parent::validateIds($this->organizationIds, 'organizationIds');
        $this->productIds               = parent::validateIds($this->productIds, 'productIds');
        $this->sourceIds                = parent::validateIds($this->sourceIds, 'sourceIds');
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object                         = parent::jsonSerialize();
        $object['clientIds']            = $this->clientIds;
        $object['organizationIds']      = $this->organizationIds;
        $object['productIds']           = $this->productIds;
        $object['sourceIds']            = $this->sourceIds;
        $object['externalIds']          = $this->externalIds;
        $object['skus']                 = $this->skus;
        $object['barCodes']             = $this->barCodes;

        return $object;
    }

    public function clean()
    {
        parent::clean();

        $this->clientIds                = parent::getCommaSeparatedIds($this->clientIds);
        $this->organizationIds          = parent::getCommaSeparatedIds($this->organizationIds);
        $this->productIds               = parent::getCommaSeparatedIds($this->productIds);
        $this->sourceIds                = parent::getCommaSeparatedIds($this->sourceIds);
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
    public function getProductIds()
    {
        return $this->productIds;
    }

    /**
     * @param null|string $productIds
     */
    public function setProductIds($productIds)
    {
        $this->productIds = $productIds;
    }

    /**
     * @return null|string
     */
    public function getSourceIds()
    {
        return $this->sourceIds;
    }

    /**
     * @param null|string $sourceIds
     */
    public function setSourceIds($sourceIds)
    {
        $this->sourceIds = $sourceIds;
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
     * @return null|string
     */
    public function getSkus()
    {
        return $this->skus;
    }

    /**
     * @param null|string $skus
     */
    public function setSkus($skus)
    {
        $this->skus = $skus;
    }

    /**
     * @return null|string
     */
    public function getBarCodes()
    {
        return $this->barCodes;
    }

    /**
     * @param null|string $barCodes
     */
    public function setBarCodes($barCodes)
    {
        $this->barCodes = $barCodes;
    }

}