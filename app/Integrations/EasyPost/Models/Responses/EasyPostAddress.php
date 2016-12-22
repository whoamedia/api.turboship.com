<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#addresses
 * Class Address
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostAddress
{

    /**
     * Unique identifier, begins with "adr_"
     * @var int
     */
    protected $id;

    /**
     * "Address"
     * @var string
     */
    protected $object;

    /**
     * Set based on which api-key you used, either "test" or "production"
     * @var string
     */
    protected $mode;

    /**
     * @var string
     */
    protected $street1;

    /**
     * @var string|null
     */
    protected $street2;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $zip;

    /**
     * ISO 3166 country code for the country the address is located in
     * @var string
     */
    protected $country;

    /**
     * Whether or not this address would be considered residential
     * @var bool
     */
    protected $residential;

    /**
     * The specific designation for the address (only relevant if the address is a carrier facility)
     * @var string|null
     */
    protected $carrier_facility;

    /**
     * Name of the person. Both name and company can be included
     * @var string
     */
    protected $name;

    /**
     * Name of the organization. Both name and company can be included
     * @var string
     */
    protected $company;

    /**
     * Phone number to reach the person or organization
     * @var string
     */
    protected $phone;

    /**
     * Email to reach the person or organization
     * @var string
     */
    protected $email;

    /**
     * Federal tax identifier of the person or organization
     * @var string
     */
    protected $federal_tax_id;

    /**
     * State tax identifier of the person or organization
     * @var string
     */
    protected $state_tax_id;

    /**
     * @var Verifications
     */
    protected $verifications;

}