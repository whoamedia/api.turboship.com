<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#pickups
 * Class PickupRate
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostPickupRate
{

    /**
     * Unique, begins with 'pickuprate_'
     * @var	string
     */
    protected $id;

    /**
     * "PickupRate"
     * @var	string
     */
    protected $object;

    /**
     * "test" or "production"
     * @var	string
     */
    protected $mode;

    /**
     * service name
     * @var	string
     */
    protected $service;

    /**
     * name of carrier
     * @var	string
     */
    protected $carrier;

    /**
     * The actual rate quote for this service
     * @var	string
     */
    protected $rate;

    /**
     * currency for the rate
     * @var	string
     */
    protected $currency;

    /**
     * the ID of the pickup this is a quote for
     * @var	string
     */
    protected $pickup_id;

    /**
     * @var	string
     */
    protected $created_at;

    /**
     * @var	string
     */
    protected $updated_at;


}