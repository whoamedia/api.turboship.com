<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#shipping-insurance
 * Class Insurance
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostInsurance
{

    use SimpleSerialize;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}