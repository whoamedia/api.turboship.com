<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

class EasyPostCarrierDetail
{

    use SimpleSerialize;

    /**
     * "CarrierDetail"
     * @var	string
     */
    protected $object;

    /**
     * The service level the associated shipment was shipped with (if available)
     * @var	string
     */
    protected $service;

    /**
     * The type of container the associated shipment was shipped in (if available)
     * @var	string
     */
    protected $container_type;

    /**
     * The estimated delivery date as provided by the carrier, in the local time zone (if available)
     * @var	string
     */
    protected $est_delivery_date_local;

    /**
     * The estimated delivery time as provided by the carrier, in the local time zone (if available)
     * @var	string
     */
    protected $est_delivery_time_local;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}