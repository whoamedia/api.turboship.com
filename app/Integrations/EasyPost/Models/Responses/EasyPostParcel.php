<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#parcels
 * Class Parcel
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostParcel
{

    use SimpleSerialize;

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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}