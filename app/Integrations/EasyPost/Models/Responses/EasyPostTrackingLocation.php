<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#tracking-detail-object
 * Class TrackingLocation
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostTrackingLocation
{

    use SimpleSerialize;

    /**
     * "TrackingLocation"
     * @var	string
     */
    protected $object;

    /**
     * The city where the scan event occurred (if available)
     * @var	string
     */
    protected $city;

    /**
     * The state where the scan event occurred (if available)
     * @var	string
     */
    protected $state;

    /**
     * The country where the scan event occurred (if available)
     * @var	string
     */
    protected $country;

    /**
     * The postal code where the scan event occurred (if available)
     * @var	string
     */
    protected $zip;


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}