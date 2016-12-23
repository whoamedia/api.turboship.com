<?php

namespace App\Integrations\EasyPost\Models\Responses;


use App\Integrations\EasyPost\Traits\SimpleSerialize;
use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see https://www.easypost.com/docs/api.html#orders
 * Class Order
 * @package App\Integrations\EasyPost\Models\Responses
 */
class EasyPostOrder
{

    use SimpleSerialize;

    /**
     * Unique, begins with "order_"
     * @var	string
     */
    protected $id;

    /**
     * "Order"
     * @var	string
     */
    protected $object;

    /**
     * An optional field that may be used in place of id in other API endpoints
     * @var	string
     */
    protected $reference;

    /**
     * "test" or "production"
     * @var	string
     */
    protected $mode;

    /**
     * The destination address
     * @var	EasyPostAddress
     */
    protected $to_address;

    /**
     * The origin address
     * @var	EasyPostAddress
     */
    protected $from_address;

    /**
     * The shipper's address, defaults to from_address
     * @var	EasyPostAddress
     */
    protected $return_address;

    /**
     * The buyer's address, defaults to to_address
     * @var	EasyPostAddress
     */
    protected $buyer_address;

    /**
     * All associated Shipment objects
     * @var	EasyPostShipment[]
     */
    protected $shipments;

    /**
     * All associated Rate objects
     * @var	EasyPostRate[]
     */
    protected $rates;

    /**
     * Any carrier errors encountered during rating
     * @var	EasyPostMessage[]
     */
    protected $messages;

    /**
     * Set true to create as a return
     * @var	boolean
     */
    protected $is_return;

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