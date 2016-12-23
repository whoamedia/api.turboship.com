<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#fee-object
 * Class Fee
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostFee
{

    use SimpleSerialize;

    /**
     * "Fee"
     * @var	string
     */
    protected $object;

    /**
     * The name of the category of fee. Possible types are "LabelFee", "PostageFee", "InsuranceFee", and "TrackerFee"
     * @var	string
     */
    protected $type;

    /**
     * USD value with sub-cent precision
     * @var	string
     */
    protected $amount;

    /**
     * Whether EasyPost has successfully charged your account for the fee
     * @var	boolean
     */
    protected $charged;

    /**
     * Whether the Fee has been refunded successfully
     * @var	boolean
     */
    protected $refunded;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->simpleSerialize();
    }

}