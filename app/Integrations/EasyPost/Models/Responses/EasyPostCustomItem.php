<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#customs-item-object
 * Class CustomsItem
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostCustomItem
{

    use SimpleSerialize;

    /**
     * Unique, begins with 'cstitem_'
     * @var	string
     */
    protected $id;

    /**
     * 'CustomsItem'
     * @var	string
     */
    protected $object;

    /**
     * Required, description of item being shipped
     * @var	string
     */
    protected $description;

    /**
     * Required, greater than zero
     * @var	float
     */
    protected $quantity;

    /**
     * (USD)	Required, greater than zero, total value (unit value * quantity)
     * @var	float
     */
    protected $value;

    /**
     * Required, greater than zero, total weight (unit weight * quantity)
     * @var	float (oz)
     */
    protected $weight;

    /**
     * Harmonized Tariff Schedule, e.g. "6109.10.0012" for Men's T-shirts
     * @var	string
     */
    protected $hs_tariff_number;

    /**
     * SKU/UPC or other product identifier
     * @var	string
     */
    protected $code;

    /**
     * Required, 2 char country code
     * @var	string
     */
    protected $origin_country;

    /**
     * 3 char currency code, default USD
     * @var	string
     */
    protected $currency;

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