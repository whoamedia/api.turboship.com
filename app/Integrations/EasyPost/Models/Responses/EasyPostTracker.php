<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#tracker-object
 * Class Tracker
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostTracker
{

    /**
     * Unique identifier, begins with "trk_"
     * @var	string
     */
    protected $id;

    /**
     * "Tracker"
     * @var	string
     */
    protected $object;

    /**
     * "test" or "production"
     * @var	string
     */
    protected $mode;

    /**
     * The tracking code provided by the carrier
     * @var	string
     */
    protected $tracking_code;

    /**
     * The current status of the package
     * Possible values are "unknown", "pre_transit", "in_transit", "out_for_delivery", "delivered", "available_for_pickup", "return_to_sender", "failure", "cancelled" or "error"
     * @var	string
     */
    protected $status;

    /**
     * The name of the person who signed for the package (if available)
     * @var	string
     */
    protected $signed_by;

    /**
     * The weight of the package as measured by the carrier in ounces (if available)
     * @var	float
     */
    protected $weight;

    /**
     * The estimated delivery date provided by the carrier (if available)
     * @var	string
     */
    protected $est_delivery_date;

    /**
     * The id of the EasyPost Shipment object associated with the Tracker (if any)
     * @var	string
     */
    protected $shipment_id;

    /**
     * The name of the carrier handling the shipment
     * @var	string
     */
    protected $carrier;

    /**
     * Array of the associated TrackingDetail objects
     * @var	EasyPostTrackingDetail[]
     */
    protected $tracking_details;

    /**
     * The associated CarrierDetail object (if available)
     * @var	EasyPostCarrierDetail
     */
    protected $carrier_detail;

    /**
     * URL to a publicly-accessible html page that shows tracking details for this tracker
     * @var	string
     */
    protected $public_url;

    /**
     * Array of the associated Fee objects
     * @var	EasyPostFee[]
     */
    protected $fees;

    /**
     * @var	string
     */
    protected $created_at;

    /**
     * @var	string
     */
    protected $updated_at;


}