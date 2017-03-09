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


    public function __construct($data = [])
    {
        $this->pendingSku               = AU::get($data['pendingSku']);
    }

    public function validate()
    {
    }

    public function clean()
    {
        $this->pendingSku               = BU::getBooleanValue($this->pendingSku);
        $this->pendingSku               = is_null($this->pendingSku) ? false : $this->pendingSku;

    }

    public function jsonSerialize()
    {
        $object['pendingSku']           = $this->pendingSku;

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

}