<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#rate-object
 * Class Rate
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostRate
{

    /**
     * unique, begins with 'rate_'
     * @var	string
     */
    protected $id;

    /**
     * "Rate"
     * @var	string
     */
    protected $object;

    /**
     * "test" or "production"
     * @var	string
     */
    protected $mode;

    /**
     * service level/name
     * @var	string
     */
    protected $service;

    /**
     * name of carrier
     * @var	string
     */
    protected $carrier;

    /**
     * ID of the CarrierAccount record used to generate this rate
     * @var	string
     */
    protected $carrier_account_id;

    /**
     * ID of the Shipment this rate belongs to
     * @var	string
     */
    protected $shipment_id;

    /**
     * the actual rate quote for this service
     * @var	string
     */
    protected $rate;

    /**
     * currency for the rate
     * @var	string
     */
    protected $currency;

    /**
     * the retail rate is the in-store rate given with no account
     * @var	string
     */
    protected $retail_rate;

    /**
     * currency for the retail rate
     * @var	string
     */
    protected $retail_currency;

    /**
     * the list rate is the non-negotiated rate given for having an account with the carrier
     * @var	string
     */
    protected $list_rate;

    /**
     * currency for the list rate
     * @var	string
     */
    protected $list_currency;

    /**
     * delivery days for this service
     * @var	int
     */
    protected $delivery_days;

    /**
     * date for delivery
     * @var	string
     */
    protected $delivery_date;

    /**
     * indicates if delivery window is guaranteed (true) or not (false)
     * @var	boolean
     */
    protected $delivery_date_guaranteed;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;


}