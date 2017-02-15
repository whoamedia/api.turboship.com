<?php

namespace App\Http\Requests\Shopify;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil AS BU;

class DownloadShopifyProducts extends BaseRequest implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var bool
     */
    protected $pendingSku;

    /**
     * @var bool
     */
    protected $importVariantInventory;


    public function __construct($data = [])
    {
        $this->pendingSku               = AU::get($data['pendingSku']);
        $this->importVariantInventory   = AU::get($data['importVariantInventory']);
    }

    public function validate()
    {
    }

    public function clean()
    {
        $this->pendingSku               = BU::getBooleanValue($this->pendingSku);
        $this->pendingSku               = is_null($this->pendingSku) ? false : $this->pendingSku;

        $this->importVariantInventory   = BU::getBooleanValue($this->importVariantInventory);
        $this->importVariantInventory   = is_null($this->importVariantInventory) ? false : $this->importVariantInventory;
    }

    public function jsonSerialize()
    {
        $object['pendingSku']           = $this->pendingSku;
        $object['importVariantInventory']   = $this->importVariantInventory;

        return $object;
    }

    /**
     * @return bool
     */
    public function getPendingSku()
    {
        return $this->pendingSku;
    }

    /**
     * @param bool $pendingSku
     */
    public function setPendingSku($pendingSku)
    {
        $this->pendingSku = $pendingSku;
    }

    /**
     * @return bool
     */
    public function getImportVariantInventory()
    {
        return $this->importVariantInventory;
    }

    /**
     * @param bool $importVariantInventory
     */
    public function setImportVariantInventory($importVariantInventory)
    {
        $this->importVariantInventory = $importVariantInventory;
    }

}