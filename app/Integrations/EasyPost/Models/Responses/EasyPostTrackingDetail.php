<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#tracking-detail-object
 * Class TrackingDetail
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostTrackingDetail
{

    use SimpleSerialize;

    /**
     * "TrackingDetail"
     * @var	string
     */
    protected $object;

    /**
     * Description of the scan event
     * @var	string
     */
    protected $message;

    /**
     * Status of the package at the time of the scan event
     * Possible values are "unknown", "pre_transit", "in_transit", "out_for_delivery", "delivered", "available_for_pickup", "return_to_sender", "failure", "cancelled" or "error"
     * @var	string
     */
    protected $status;

    /**
     * The timestamp when the tracking scan occurred
     * @var	string
     */
    protected $datetime;

    /**
     * The original source of the information for this scan event, usually the carrier
     * @var	string
     */
    protected $source;

    /**
     * The location associated with the scan event
     * @var	EasyPostTrackingLocation
     */
    protected $tracking_location;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}