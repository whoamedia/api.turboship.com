<?php

namespace App\Services\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see     https://help.shopify.com/api/reference/order#cancel
 * Class CancelShopifyOrder
 * @package App\Services\Shopify\Models\Requests
 */
class CancelShopifyOrder implements \JsonSerializable
{

    /**
     * Amount to refund
     *  If set, Shopify will attempt to void/refund the payment depending on the status.
     * (default: false)
     * @var float|null
     */
    protected $amount;

    /**
     * Restock the items for this order back to your store
     * (default: false)
     * @var bool
     */
    protected $restock;

    /**
     * The reason for the order cancellation
     * one of customer, inventory, fraud, other
     * (default: other)
     * @var string
     */
    protected $reason;

    /**
     * Send an email to the customer notifying them of the cancellation
     * Default false
     * @var bool
     */
    protected $email;

    /**
     * Required for some more complex refund situations.
     * Default false
     * @var bool
     */
    protected $refund;


    public function __construct($data = [])
    {
        $this->amount                   = AU::get($data['amount']);
        $this->restock                  = AU::get($data['restock'], false);
        $this->reason                   = AU::get($data['reason'], 'other');
        $this->email                    = AU::get($data['email'], false);
        $this->refund                   = AU::get($data['refund'], false);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['amount']               = $this->amount;
        $object['restock']              = $this->restock;
        $object['reason']               = $this->reason;
        $object['email']                = $this->email;
        $object['refund']               = $this->refund;

        return $object;
    }
}