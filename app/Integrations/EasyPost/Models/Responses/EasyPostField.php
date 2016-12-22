<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#carrier-account-object
 * Class Field
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostField
{

    /**
     * Each key in the sub-objects of a CarrierAccount's fields is the name of a settable field
     * @var	string
     */
    protected $key;

    /**
     * The visibility value is used to control form field types, and is discussed in the CarrierType section
     * @var	string
     */
    protected $visibility;

    /**
     * The label value is used in form rendering to display a more precise field name
     * @var	string
     */
    protected $label;

    /**
     * Checkbox fields use "0" and "1" as False and True, all other field types present plaintext, partly-masked, or masked credential data for reference
     * @var	string
     */
    protected $value;


}