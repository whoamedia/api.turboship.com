<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#scan-form-object
 * Class ScanForm
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostScanForm
{

    /**
     * Unique, begins with "sf_"
     * @var	string
     */
    protected $id;

    /**
     * "ScanForm"
     * @var	string
     */
    protected $object;

    /**
     * Current status. Possible values are "creating", "created" and "failed"
     * @var	string
     */
    protected $status;

    /**
     * Human readable message explaining any failures
     * @var	string
     */
    protected $message;

    /**
     * Address the will be Shipments shipped from
     * @var	EasyPostAddress
     */
    protected $address;

    /**
     * Tracking codes included on the ScanForm
     * @var	string[]
     */
    protected $tracking_codes;

    /**
     * Url of the document
     * @var	string
     */
    protected $form_url;

    /**
     * File format of the document
     * @var	string
     */
    protected $form_file_type;

    /**
     * The id of the associated Batch. Unique, starts with "batch_"
     * @var	string
     */
    protected $batch_id;

    /**
     * @var	string
     */
    protected $created_at;

    /**
     * @var	string
     */
    protected $updated_at;


}