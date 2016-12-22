<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#shipments
 * Class Shipment
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostShipment
{

    /**
     * Unique, begins with "shp_"
     * @var string
     */
    protected $id;

    /**
     * "Shipment"
     * @var string
     */
    protected $object;

    /**
     * An optional field that may be used in place of id in other API endpoints
     * @var string
     */
    protected $reference;

    /**
     * "test" or "production"
     * @var string
     */
    protected $mode;

    /**
     * The destination address
     * @var EasyPostAddress
     */
    protected $to_address;

    /**
     * The origin address
     * @var EasyPostAddress
     */
    protected $from_address;

    /**
     * The shipper's address, defaults to from_address
     * @var EasyPostAddress
     */
    protected $return_address;

    /**
     * The buyer's address, defaults to to_address
     * @var EasyPostAddress
     */
    protected $buyer_address;

    /**
     * The dimensions and weight of the package
     * @var EasyPostParcel
     */
    protected $parcel;

    /**
     * Information for the processing of customs
     * @var EasyPostCustomsInfo
     */
    protected $customs_info;

    /**
     * Document created to manifest and scan multiple shipments
     * @var EasyPostScanForm
     */
    protected $scan_form;

    /**
     * All associated Form objects
     * @var EasyPostForm[]
     */
    protected $forms;

    /**
     * The associated Insurance object
     * @var EasyPostInsurance
     */
    protected $insurance;

    /**
     * All associated Rate objects
     * @var EasyPostRate[]
     */
    protected $rates;

    /**
     * The specific rate purchased for the shipment, or null if unpurchased or purchased through another mechanism
     * @var EasyPostRate
     */
    protected $selected_rate;

    /**
     * The associated PostageLabel object
     * @var EasyPostPostageLabel
     */
    protected $postage_label;

    /**
     * Any carrier errors encountered during rating, discussed in more depth below
     * @var EasyPostMessage[]
     */
    protected $messages;

    /**
     * All of the options passed to the shipment, discussed in more depth below
     * @var EasyPostOptions
     */
    protected $options;

    /**
     * Set true to create as a return, discussed in more depth below
     * @var boolean
     */
    protected $is_return;

    /**
     * If purchased, the tracking code will appear here as well as within the Tracker object
     * @var string
     */
    protected $tracking_code;

    /**
     * The USPS zone of the shipment, if purchased with USPS
     * @var string
     */
    protected $usps_zone;

    /**
     * The current tracking status of the shipment
     * @var string
     */
    protected $status;

    /**
     * The associated Tracker object
     * @var EasyPostTracker
     */
    protected $tracker;

    /**
     * The associated Fee objects charged to the billing user account
     * @var EasyPostFee[]
     */
    protected $fees;

    /**
     * The current status of the shipment refund process. Possible values are "submitted", "refunded", "rejected".
     * @var string
     */
    protected $refund_status;

    /**
     * The ID of the batch that contains this shipment, if any
     * @var string
     */
    protected $batch_id;

    /**
     * The current state of the associated BatchShipment
     * @var string
     */
    protected $batch_status;

    /**
     * The current message of the associated BatchShipment
     * @var string
     */
    protected $batch_message;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;


}