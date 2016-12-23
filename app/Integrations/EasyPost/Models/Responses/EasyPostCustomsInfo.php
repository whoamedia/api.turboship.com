<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#customs-info-object
 * Class CustomsInfo
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostCustomsInfo
{

    use SimpleSerialize;

    /**
     * Unique, begins with 'cstinfo_'
     * @var	string
     */
    protected $id;

    /**
     * 'CustomsInfo'
     * @var	string
     */
    protected $object;

    /**
     * "EEL" or "PFC"
     * value less than $2500: "NOEEI 30.37(a)"; value greater than $2500: see Customs Guide
     * @var	string
     */
    protected $eel_pfc;

    /**
     * "documents", "gift", "merchandise", "returned_goods", "sample", or "other"
     * @var	string
     */
    protected $contents_type;

    /**
     * Human readable description of content. Required for certain carriers and always required if contents_type is "other"
     * @var	string
     */
    protected $contents_explanation;

    /**
     * Electronically certify the information provided
     * @var	boolean
     */
    protected $customs_certify;

    /**
     * Required if customs_certify is true
     * @var	string
     */
    protected $customs_signer;

    /**
     * "abandon" or "return", defaults to "return"
     * @var	string
     */
    protected $non_delivery_option;

    /**
     * "none", "other", "quarantine", or "sanitary_phytosanitary_inspection"
     * @var	string
     */
    protected $restriction_type;

    /**
     * Required if restriction_type is not "none"
     * @var	string
     */
    protected $restriction_comments;

    /**
     * Describes to products being shipped
     * @var	EasyPostCustomItem[]
     */
    protected $customs_items;

    /**
     * @var	string
     */
    protected $created_at;

    /**
     * @var	string
     */
    protected $updated_at;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}