<?php

namespace App\Http\Requests\Shopify;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use App\Http\Requests\BaseRequest;
use App\Integrations\Shopify\Models\Requests\GetShopifyProducts;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\BooleanUtil AS BU;

class DownloadShopifyProducts extends GetShopifyProducts implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var bool|null
     */
    protected $pendingSku;


    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->pendingSku               = AU::get($data['pendingSku']);
    }

    public function validate()
    {
    }

    public function clean()
    {
        $this->pendingSku               = BU::getBooleanValue($this->pendingSku);
    }

    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        return $object;
    }

    /**
     * @return bool|null
     */
    public function getPendingSku()
    {
        return $this->pendingSku;
    }

    /**
     * @param bool|null $pendingSku
     */
    public function setPendingSku($pendingSku)
    {
        $this->pendingSku = $pendingSku;
    }

}