<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#carrier-account-object
 * Class CarrierAccount
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostCarrierAccount
{

    /**
     * Unique, begins with "ca_"
     * @var	string
     */
    protected $id;

    /**
     * "CarrierAccount"
     * @var	string
     */
    protected $object;

    /**
     * The name of the carrier type. Note that "EndiciaAccount" is the current USPS integration account type
     * @var	string
     */
    protected $type;

    /**
     * Contains "credentials" and/or "test_credentials", or may be empty
     * @var	EasyPostFields
     */
    protected $fields;

    /**
     * If clone is true, only the reference and description are possible to update
     * @var	boolean
     */
    protected $clone;

    /**
     * An optional, user-readable field to help distinguish accounts
     * @var	string
     */
    protected $description;

    /**
     * An optional field that may be used in place of carrier_account_id in other API endpoints
     * @var	string
     */
    protected $reference;

    /**
     * The name used when displaying a readable value for the type of the account
     * @var	string
     */
    protected $readable;

    /**
     * Unlike the "credentials" object contained in "fields", this nullable object contains just raw credential pairs for client library consumption
     * @var	object
     */
    protected $credentials;

    /**
     * Unlike the "test_credentials" object contained in "fields", this nullable object contains just raw test_credential pairs for client library consumption
     * @var	object
     */
    protected $test_credentials;

    /**
     * @var	string
     */
    protected $created_at;

    /**
     * @var	string
     */
    protected $updated_at;


}