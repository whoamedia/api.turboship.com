<?php

namespace App\Integrations\EasyPost\Models\Responses;


/**
 * @see https://www.easypost.com/docs/api.html#parcels
 * Class Parcel
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostParcel
{

    /**
     * Unique, begins with "prcl_"
     * @var string
     */
    protected $id;

    /**
     * "Parcel"
     * @var string
     */
    protected $object;

    /**
     * "test" or "production"
     * @var string
     */
    protected $mode;

    /**
     * Inches
     * Required if predefined_package is empty
     * @var float
     */
    protected $length;

    /**
     * Inches
     * Required if predefined_package is empty
     * @var float
     */
    protected $width;

    /**
     * Inches
     * Required if predefined_package is empty
     * @var float
     */
    protected $height;

    /**
     * @var string|null
     */
    protected $predefined_package;

    /**
     * Ounces. Always required
     * @var float
     */
    protected $weight;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

}