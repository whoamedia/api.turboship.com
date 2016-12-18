<?php

namespace App\Services\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

/**
 * @see     https://help.shopify.com/api/reference/order
 * Class ShopifyOrder
 * @package App\Services\Shopify\Models\Responses
 */
class ShopifyOrder implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ShopifyAddress|null
     */
    protected $billing_address;

    /**
     * @var string|null
     */
    protected $browser_ip;

    /**
     * Indicates whether or not the person who placed the order would like to receive email updates from the shop.
     * This is set when checking the "I want to receive occasional emails about new products, promotions and other news" checkbox during checkout.
     * @var bool
     */
    protected $buyer_accepts_marketing;

    /**
     * The reason why the order was cancelled. If the order was not cancelled, this value is "null."
     * If the order was cancelled, the value will be one of the following:
     * customer: The customer changed or cancelled the order.
     * fraud: The order was fraudulent.
     * inventory: Items in the order were not in inventory.
     * other: The order was cancelled for a reason not in the list above.
     * @var string|null
     */
    protected $cancel_reason;

    /**
     * @var string|null
     */
    protected $cancelled_at;

    /**
     * Unique identifier for a particular cart that is attached to a particular order.
     * @var string
     */
    protected $cart_token;

    /**
     * @var ShopifyClientDetail
     */
    protected $client_details;

    /**
     * The date and time when the order was closed. If the order was closed, the API returns this value in ISO 8601 format.
     * If the order was not closed, this value is null.
     * @var string|null
     */
    protected $closed_at;

    /**
     * @var string|null
     */
    protected $checkout_id;

    /**
     * @var string|null
     */
    protected $checkout_token;

    /**
     * @var bool
     */
    protected $confirmed;

    /**
     * @var string|null
     */
    protected $contact_email;

    /**
     * @var string|null
     */
    protected $device_id;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    /**
     * The three letter code (ISO 4217) for the currency used for the payment.
     * @var string
     */
    protected $currency;

    /**
     * @var ShopifyCustomer
     */
    protected $customer;

    /**
     * Applicable discount codes that can be applied to the order. If no codes exist the value will default to blank.
     * A Discount code will include the following fields:
     * amount: The amount of the discount.
     * code: The discount code.
     * type: The type of discount. Can be one of: "percentage", "shipping", "fixed_amount" (default).
     * @var array
     */
    protected $discount_codes;

    /**
     * The customer's email address. Is required when a billing address is present.
     * @var string|null
     */
    protected $email;

    /**
     * pending: The finances are pending.
     * authorized: The finances have been authorized.
     * partially_paid: The finances have been partially paid.
     * paid: The finances have been paid. (This is the default value.)
     * partially_refunded: The finances have been partially refunded.
     * refunded: The finances have been refunded.
     * voided: The finances have been voided.
     * @var string
     */
    protected $financial_status;

    /**
     * @var ShopifyFulfillment[]
     */
    protected $fulfillments;

    /**
     * fulfilled: Every line item in the order has been fulfilled.
     * null: None of the line items in the order have been fulfilled.
     * partial: At least one line item in the order has been fulfilled.
     * @var string|null
     */
    protected $fulfillment_status;

    /**
     * @var string
     */
    protected $tags;

    /**
     * The URL for the page where the buyer landed when entering the shop.
     * @var string
     */
    protected $landing_site;

    /**
     * @var string|null
     */
    protected $landing_site_ref;

    /**
     * @var ShopifyOrderLineItem[]
     */
    protected $line_items;

    /**
     * Only present on orders processed at point of sale.
     * The unique numeric identifier for the physical location at which the order was processed.
     * @var int|null
     */
    protected $location_id;

    /**
     * The customer's order name as represented by a number.
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $note;

    /**
     * Extra information that is added to the order.
     * Each array entry must contain a hash with "name" and "value"
     * @var array
     */
    protected $note_attributes;

    /**
     * Numerical identifier unique to the shop. A number is sequential and starts at 1000.
     * @var int
     */
    protected $number;

    /**
     * A unique numeric identifier for the order.
     * This one is used by the shop owner and customer.
     * This is different from the id property, which is also a unique numeric identifier for the order, but used for API purposes.
     * @var int
     */
    protected $order_number;

    /**
     * @var string
     */
    protected $gateway;

    /**
     * @var bool
     */
    protected $test;

    /**
     * The list of all payment gateways used for the order.
     * @var array
     */
    protected $payment_gateway_names;

    /**
     * The date and time when the order was imported
     * This value can be set to dates in the past when importing from other systems
     * If no value is provided, it will be auto-generated.
     * @var string
     */
    protected $processed_at;

    /**
     * States the type of payment processing method
     * Valid values are: checkout, direct, manual, offsite or express.
     * @var string
     */
    protected $processing_method;

    /**
     * @var string|null
     */
    protected $reference;

    /**
     * The website that the customer clicked on to come to the shop
     * @var string
     */
    protected $referring_site;

    /**
     * The list of refunds applied to the order.
     * @var array
     */
    protected $refunds;

    /**
     * @var ShopifyAddress
     */
    protected $shipping_address;

    /**
     * @var ShopifyShippingLine[]
     */
    protected $shipping_lines;

    /**
     * @var string|null
     */
    protected $source_identifier;

    /**
     * Where the order originated
     * May only be set during creation, and is not writeable thereafter.
     * @var string
     */
    protected $source_name;

    /**
     * @var string|null
     */
    protected $source_url;

    /**
     * Price of the order before shipping and taxes
     * @var float
     */
    protected $subtotal_price;

    /**
     * @var ShopifyTaxLine[]
     */
    protected $tax_lines;

    /**
     * States whether or not taxes are included in the order subtotal.
     * Valid values are "true" or "false".
     * @var bool
     */
    protected $taxes_included;

    /**
     * Unique identifier for a particular order.
     * @var string
     */
    protected $token;

    /**
     * The total amount of the discounts to be applied to the price of the order.
     * @var float
     */
    protected $total_discounts;

    /**
     * The sum of all the prices of all the items in the order.
     * @var float
     */
    protected $total_line_items_price;

    /**
     * The sum of all the prices of all the items in the order, taxes and discounts included (must be positive).
     * @var float
     */
    protected $total_price;

    /**
     * @var float
     */
    protected $total_price_usd;

    /**
     * The sum of all the taxes applied to the order (must be positive).
     * @var float
     */
    protected $total_tax;

    /**
     * The sum of all the weights of the line items in the order, in grams.
     * @var int
     */
    protected $total_weight;

    /**
     * Only present on orders processed at point of sale.
     * The unique numerical identifier for the user logged into the terminal at the time the order was processed at.
     * @var int|null
     */
    protected $user_id;

    /**
     * The URL pointing to the order status web page.
     * The URL will be null unless the order was created from a checkout.
     * @var string|null
     */
    protected $order_status_url;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);

        $this->billing_address          = AU::get($data['billing_address']);
        if (!is_null($this->billing_address))
            $this->billing_address      = new ShopifyAddress($this->billing_address);

        $this->browser_ip               = AU::get($data['browser_ip']);
        $this->buyer_accepts_marketing  = AU::get($data['buyer_accepts_marketing']);
        $this->cancel_reason            = AU::get($data['cancel_reason']);
        $this->cancelled_at             = AU::get($data['cancelled_at']);
        $this->cart_token               = AU::get($data['cart_token']);

        $this->client_details           = AU::get($data['client_details']);
        if (!is_null($this->client_details))
            $this->client_details       = new ShopifyClientDetail($this->client_details);

        $this->closed_at                = AU::get($data['closed_at']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
        $this->currency                 = AU::get($data['currency']);

        $this->customer                 = AU::get($data['customer']);
        if (!is_null($this->customer))
            $this->customer             = new ShopifyCustomer($this->customer);

        $this->discount_codes           = AU::get($data['discount_codes']);
        $this->email                    = AU::get($data['email']);
        $this->financial_status         = AU::get($data['financial_status']);

        $this->fulfillments             = [];
        $fulfillments                   = AU::get($data['fulfillments']);
        if (!is_null($fulfillments))
        {
            foreach ($fulfillments AS $item)
                $this->fulfillments[]   = new ShopifyFulfillment($item);
        }

        $this->fulfillment_status       = AU::get($data['fulfillment_status']);
        $this->tags                     = AU::get($data['tags']);
        $this->landing_site             = AU::get($data['landing_site']);

        $this->line_items               = [];
        $line_items                     = AU::get($data['line_items']);
        foreach ($line_items AS $item)
        {
            $this->line_items[]         = new ShopifyOrderLineItem($item);
        }

        $this->location_id              = AU::get($data['location_id']);
        $this->name                     = AU::get($data['name']);
        $this->note                     = AU::get($data['note']);
        $this->note_attributes          = AU::get($data['note_attributes']);
        $this->number                   = AU::get($data['number']);
        $this->order_number             = AU::get($data['order_number']);
        $this->payment_gateway_names    = AU::get($data['payment_gateway_names']);
        $this->processed_at             = AU::get($data['processed_at']);
        $this->processing_method        = AU::get($data['processing_method']);
        $this->referring_site           = AU::get($data['referring_site']);
        $this->refunds                  = AU::get($data['refunds']);

        $this->shipping_address         = AU::get($data['shipping_address']);
        if (!is_null($this->shipping_address))
            $this->shipping_address     = new ShopifyAddress($this->shipping_address);

        $this->shipping_lines           = [];
        $shipping_lines                 = AU::get($data['shipping_lines']);
        foreach ($shipping_lines AS $item)
        {
            $this->shipping_lines[]     = new ShopifyShippingLine($item);
        }

        $this->source_name              = AU::get($data['source_name']);
        $this->subtotal_price           = AU::get($data['subtotal_price']);

        $this->tax_lines                = [];
        $tax_lines                      = AU::get($data['tax_lines']);
        foreach ($tax_lines AS $item)
        {
            $this->tax_lines[]          = new ShopifyTaxLine($item);
        }

        $this->taxes_included           = AU::get($data['taxes_included']);
        $this->token                    = AU::get($data['token']);
        $this->total_discounts          = AU::get($data['total_discounts']);
        $this->total_line_items_price   = AU::get($data['total_line_items_price']);
        $this->total_price              = AU::get($data['total_price']);
        $this->total_tax                = AU::get($data['total_tax']);
        $this->total_weight             = AU::get($data['total_weight']);
        $this->user_id                  = AU::get($data['user_id']);
        $this->order_status_url         = AU::get($data['order_status_url']);


        $this->checkout_id              = AU::get($data['checkout_id']);
        $this->checkout_token           = AU::get($data['checkout_token']);
        $this->confirmed                = AU::get($data['confirmed']);
        $this->contact_email            = AU::get($data['contact_email']);
        $this->device_id                = AU::get($data['device_id']);
        $this->landing_site_ref         = AU::get($data['landing_site_ref']);
        $this->gateway                  = AU::get($data['gateway']);
        $this->test                     = AU::get($data['test']);
        $this->reference                = AU::get($data['reference']);
        $this->source_identifier        = AU::get($data['source_identifier']);
        $this->source_url               = AU::get($data['source_url']);
        $this->total_price_usd          = AU::get($data['total_price_usd']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;

        $object['billing_address']      = is_null($this->billing_address) ? null : $this->billing_address->jsonSerialize();
        $object['browser_ip']           = $this->browser_ip;
        $object['buyer_accepts_marketing']  = $this->buyer_accepts_marketing;
        $object['cancel_reason']        = $this->cancel_reason;
        $object['cancelled_at']         = $this->cancelled_at;
        $object['cart_token']           = $this->cart_token;
        $object['client_details']       = is_null($this->client_details) ? null : $this->client_details->jsonSerialize();
        $object['closed_at']            = $this->closed_at;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;
        $object['currency']             = $this->currency;
        $object['customer']             = is_null($this->customer) ? null : $this->customer->jsonSerialize();
        $object['discount_codes']       = $this->discount_codes;
        $object['email']                = $this->email;
        $object['financial_status']     = $this->financial_status;

        $object['fulfillments']         = [];
        foreach ($this->fulfillments AS $item)
            $object['fulfillments'][]   = $item->jsonSerialize();

        $object['fulfillment_status']   = $this->fulfillment_status;
        $object['tags']                 = $this->tags;
        $object['landing_site']         = $this->landing_site;

        $object['line_items']           = [];
        foreach ($this->line_items AS $item)
            $object['line_items'][]     = $item->jsonSerialize();

        $object['location_id']          = $this->location_id;
        $object['name']                 = $this->name;
        $object['note']                 = $this->note;
        $object['note_attributes']      = $this->note_attributes;
        $object['number']               = $this->number;
        $object['order_number']         = $this->order_number;
        $object['payment_gateway_names']= $this->payment_gateway_names;
        $object['processed_at']         = $this->processed_at;
        $object['processing_method']    = $this->processing_method;
        $object['referring_site']       = $this->referring_site;
        $object['refunds']              = $this->refunds;
        $object['shipping_address']     = is_null($this->shipping_address) ? null : $this->shipping_address->jsonSerialize();

        $object['shipping_lines']       = [];
        foreach ($this->shipping_lines AS $item)
            $object['shipping_lines'][] = $item->jsonSerialize();

        $object['source_name']          = $this->source_name;
        $object['subtotal_price']       = $this->subtotal_price;

        $object['tax_lines']            = [];
        foreach ($this->tax_lines AS $item)
            $object['tax_lines'][]      = $item->jsonSerialize();

        $object['taxes_included']       = $this->taxes_included;
        $object['token']                = $this->token;
        $object['total_discounts']      = $this->total_discounts;
        $object['total_line_items_price']= $this->total_line_items_price;
        $object['total_price']          = $this->total_price;
        $object['total_tax']            = $this->total_tax;
        $object['total_weight']         = $this->total_weight;
        $object['user_id']              = $this->user_id;
        $object['order_status_url']     = $this->order_status_url;


        $object['checkout_id']          = $this->checkout_id;
        $object['checkout_token']       = $this->checkout_token;
        $object['confirmed']            = $this->confirmed;
        $object['contact_email']        = $this->contact_email;
        $object['device_id']            = $this->device_id;
        $object['landing_site_ref']     = $this->landing_site_ref;
        $object['gateway']              = $this->gateway;
        $object['test']                 = $this->test;
        $object['reference']            = $this->reference;
        $object['source_identifier']    = $this->source_identifier;
        $object['source_url']           = $this->source_url;
        $object['total_price_usd']      = $this->total_price_usd;

        return $object;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return ShopifyAddress|null
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    /**
     * @param ShopifyAddress|null $billing_address
     */
    public function setBillingAddress($billing_address)
    {
        $this->billing_address = $billing_address;
    }

    /**
     * @return null|string
     */
    public function getBrowserIp()
    {
        return $this->browser_ip;
    }

    /**
     * @param null|string $browser_ip
     */
    public function setBrowserIp($browser_ip)
    {
        $this->browser_ip = $browser_ip;
    }

    /**
     * @return boolean
     */
    public function isBuyerAcceptsMarketing()
    {
        return $this->buyer_accepts_marketing;
    }

    /**
     * @param boolean $buyer_accepts_marketing
     */
    public function setBuyerAcceptsMarketing($buyer_accepts_marketing)
    {
        $this->buyer_accepts_marketing = $buyer_accepts_marketing;
    }

    /**
     * @return null|string
     */
    public function getCancelReason()
    {
        return $this->cancel_reason;
    }

    /**
     * @param null|string $cancel_reason
     */
    public function setCancelReason($cancel_reason)
    {
        $this->cancel_reason = $cancel_reason;
    }

    /**
     * @return null|string
     */
    public function getCancelledAt()
    {
        return $this->cancelled_at;
    }

    /**
     * @param null|string $cancelled_at
     */
    public function setCancelledAt($cancelled_at)
    {
        $this->cancelled_at = $cancelled_at;
    }

    /**
     * @return string
     */
    public function getCartToken()
    {
        return $this->cart_token;
    }

    /**
     * @param string $cart_token
     */
    public function setCartToken($cart_token)
    {
        $this->cart_token = $cart_token;
    }

    /**
     * @return ShopifyClientDetail
     */
    public function getClientDetails()
    {
        return $this->client_details;
    }

    /**
     * @param ShopifyClientDetail $client_details
     */
    public function setClientDetails($client_details)
    {
        $this->client_details = $client_details;
    }

    /**
     * @return null|string
     */
    public function getClosedAt()
    {
        return $this->closed_at;
    }

    /**
     * @param null|string $closed_at
     */
    public function setClosedAt($closed_at)
    {
        $this->closed_at = $closed_at;
    }

    /**
     * @return null|string
     */
    public function getCheckoutId()
    {
        return $this->checkout_id;
    }

    /**
     * @param null|string $checkout_id
     */
    public function setCheckoutId($checkout_id)
    {
        $this->checkout_id = $checkout_id;
    }

    /**
     * @return null|string
     */
    public function getCheckoutToken()
    {
        return $this->checkout_token;
    }

    /**
     * @param null|string $checkout_token
     */
    public function setCheckoutToken($checkout_token)
    {
        $this->checkout_token = $checkout_token;
    }

    /**
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param boolean $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return null|string
     */
    public function getContactEmail()
    {
        return $this->contact_email;
    }

    /**
     * @param null|string $contact_email
     */
    public function setContactEmail($contact_email)
    {
        $this->contact_email = $contact_email;
    }

    /**
     * @return null|string
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * @param null|string $device_id
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return ShopifyCustomer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param ShopifyCustomer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return array
     */
    public function getDiscountCodes()
    {
        return $this->discount_codes;
    }

    /**
     * @param array $discount_codes
     */
    public function setDiscountCodes($discount_codes)
    {
        $this->discount_codes = $discount_codes;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFinancialStatus()
    {
        return $this->financial_status;
    }

    /**
     * @param string $financial_status
     */
    public function setFinancialStatus($financial_status)
    {
        $this->financial_status = $financial_status;
    }

    /**
     * @return ShopifyFulfillment[]
     */
    public function getFulfillments()
    {
        return $this->fulfillments;
    }

    /**
     * @param ShopifyFulfillment[] $fulfillments
     */
    public function setFulfillments($fulfillments)
    {
        $this->fulfillments = $fulfillments;
    }

    /**
     * @return null|string
     */
    public function getFulfillmentStatus()
    {
        return $this->fulfillment_status;
    }

    /**
     * @param null|string $fulfillment_status
     */
    public function setFulfillmentStatus($fulfillment_status)
    {
        $this->fulfillment_status = $fulfillment_status;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getLandingSite()
    {
        return $this->landing_site;
    }

    /**
     * @param string $landing_site
     */
    public function setLandingSite($landing_site)
    {
        $this->landing_site = $landing_site;
    }

    /**
     * @return null|string
     */
    public function getLandingSiteRef()
    {
        return $this->landing_site_ref;
    }

    /**
     * @param null|string $landing_site_ref
     */
    public function setLandingSiteRef($landing_site_ref)
    {
        $this->landing_site_ref = $landing_site_ref;
    }

    /**
     * @return ShopifyOrderLineItem[]
     */
    public function getLineItems()
    {
        return $this->line_items;
    }

    /**
     * @param ShopifyOrderLineItem[] $line_items
     */
    public function setLineItems($line_items)
    {
        $this->line_items = $line_items;
    }

    /**
     * @return int|null
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * @param int|null $location_id
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param null|string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return array
     */
    public function getNoteAttributes()
    {
        return $this->note_attributes;
    }

    /**
     * @param array $note_attributes
     */
    public function setNoteAttributes($note_attributes)
    {
        $this->note_attributes = $note_attributes;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getOrderNumber()
    {
        return $this->order_number;
    }

    /**
     * @param int $order_number
     */
    public function setOrderNumber($order_number)
    {
        $this->order_number = $order_number;
    }

    /**
     * @return string
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param string $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return boolean
     */
    public function isTest()
    {
        return $this->test;
    }

    /**
     * @param boolean $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return array
     */
    public function getPaymentGatewayNames()
    {
        return $this->payment_gateway_names;
    }

    /**
     * @param array $payment_gateway_names
     */
    public function setPaymentGatewayNames($payment_gateway_names)
    {
        $this->payment_gateway_names = $payment_gateway_names;
    }

    /**
     * @return string
     */
    public function getProcessedAt()
    {
        return $this->processed_at;
    }

    /**
     * @param string $processed_at
     */
    public function setProcessedAt($processed_at)
    {
        $this->processed_at = $processed_at;
    }

    /**
     * @return string
     */
    public function getProcessingMethod()
    {
        return $this->processing_method;
    }

    /**
     * @param string $processing_method
     */
    public function setProcessingMethod($processing_method)
    {
        $this->processing_method = $processing_method;
    }

    /**
     * @return null|string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param null|string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReferringSite()
    {
        return $this->referring_site;
    }

    /**
     * @param string $referring_site
     */
    public function setReferringSite($referring_site)
    {
        $this->referring_site = $referring_site;
    }

    /**
     * @return array
     */
    public function getRefunds()
    {
        return $this->refunds;
    }

    /**
     * @param array $refunds
     */
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;
    }

    /**
     * @return ShopifyAddress
     */
    public function getShippingAddress()
    {
        return $this->shipping_address;
    }

    /**
     * @param ShopifyAddress $shipping_address
     */
    public function setShippingAddress($shipping_address)
    {
        $this->shipping_address = $shipping_address;
    }

    /**
     * @return ShopifyShippingLine[]
     */
    public function getShippingLines()
    {
        return $this->shipping_lines;
    }

    /**
     * @param ShopifyShippingLine[] $shipping_lines
     */
    public function setShippingLines($shipping_lines)
    {
        $this->shipping_lines = $shipping_lines;
    }

    /**
     * @return null|string
     */
    public function getSourceIdentifier()
    {
        return $this->source_identifier;
    }

    /**
     * @param null|string $source_identifier
     */
    public function setSourceIdentifier($source_identifier)
    {
        $this->source_identifier = $source_identifier;
    }

    /**
     * @return string
     */
    public function getSourceName()
    {
        return $this->source_name;
    }

    /**
     * @param string $source_name
     */
    public function setSourceName($source_name)
    {
        $this->source_name = $source_name;
    }

    /**
     * @return null|string
     */
    public function getSourceUrl()
    {
        return $this->source_url;
    }

    /**
     * @param null|string $source_url
     */
    public function setSourceUrl($source_url)
    {
        $this->source_url = $source_url;
    }

    /**
     * @return float
     */
    public function getSubtotalPrice()
    {
        return $this->subtotal_price;
    }

    /**
     * @param float $subtotal_price
     */
    public function setSubtotalPrice($subtotal_price)
    {
        $this->subtotal_price = $subtotal_price;
    }

    /**
     * @return ShopifyTaxLine[]
     */
    public function getTaxLines()
    {
        return $this->tax_lines;
    }

    /**
     * @param ShopifyTaxLine[] $tax_lines
     */
    public function setTaxLines($tax_lines)
    {
        $this->tax_lines = $tax_lines;
    }

    /**
     * @return boolean
     */
    public function isTaxesIncluded()
    {
        return $this->taxes_included;
    }

    /**
     * @param boolean $taxes_included
     */
    public function setTaxesIncluded($taxes_included)
    {
        $this->taxes_included = $taxes_included;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return float
     */
    public function getTotalDiscounts()
    {
        return $this->total_discounts;
    }

    /**
     * @param float $total_discounts
     */
    public function setTotalDiscounts($total_discounts)
    {
        $this->total_discounts = $total_discounts;
    }

    /**
     * @return float
     */
    public function getTotalLineItemsPrice()
    {
        return $this->total_line_items_price;
    }

    /**
     * @param float $total_line_items_price
     */
    public function setTotalLineItemsPrice($total_line_items_price)
    {
        $this->total_line_items_price = $total_line_items_price;
    }

    /**
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->total_price;
    }

    /**
     * @param float $total_price
     */
    public function setTotalPrice($total_price)
    {
        $this->total_price = $total_price;
    }

    /**
     * @return float
     */
    public function getTotalPriceUsd()
    {
        return $this->total_price_usd;
    }

    /**
     * @param float $total_price_usd
     */
    public function setTotalPriceUsd($total_price_usd)
    {
        $this->total_price_usd = $total_price_usd;
    }

    /**
     * @return float
     */
    public function getTotalTax()
    {
        return $this->total_tax;
    }

    /**
     * @param float $total_tax
     */
    public function setTotalTax($total_tax)
    {
        $this->total_tax = $total_tax;
    }

    /**
     * @return int
     */
    public function getTotalWeight()
    {
        return $this->total_weight;
    }

    /**
     * @param int $total_weight
     */
    public function setTotalWeight($total_weight)
    {
        $this->total_weight = $total_weight;
    }

    /**
     * @return int|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return null|string
     */
    public function getOrderStatusUrl()
    {
        return $this->order_status_url;
    }

    /**
     * @param null|string $order_status_url
     */
    public function setOrderStatusUrl($order_status_url)
    {
        $this->order_status_url = $order_status_url;
    }

}