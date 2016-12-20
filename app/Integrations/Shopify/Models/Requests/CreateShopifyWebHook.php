<?php

namespace App\Integrations\Shopify\Models\Requests;


use jamesvweston\Utilities\ArrayUtil AS AU;

class CreateShopifyWebHook implements \JsonSerializable
{

    /**
     * app/uninstalled
     * carts/create
     * carts/update
     * checkouts/create
     * checkouts/delete
     * checkouts/update
     * collections/create
     * collections/delete
     * collections/update
     * customer_groups/create
     * customer_groups/delete
     * customer_groups/update
     * customers/create
     * customers/delete
     * customers/disable
     * customers/enable
     * customers/update
     * fulfillment_events/create
     * fulfillment_events/delete
     * fulfillments/create
     * fulfillments/update
     * order_transactions/create
     * orders/cancelled
     * orders/create
     * orders/delete
     * orders/fulfilled
     * orders/paid
     * orders/partially_fulfilled
     * orders/updated
     * products/create
     * products/delete
     * products/update
     * refunds/create
     * shop/update
     * themes/create
     * themes/delete
     * themes/publish
     * themes/update
     *
     * @var string
     */
    protected $topic;

    /**
     * The URI where the webhook should send the POST request when the event occurs
     * @var string
     */
    protected $address;

    /**
     * The format in which the webhook should send the data
     * Valid values are json and xml
     * @var string
     */
    protected $format;

    /**
     * (Optional) An array of fields which should be included in webhooks.
     * @var string|null
     */
    protected $fields;


    public function __construct($data = [])
    {
        $this->topic                    = AU::get($data['topic']);
        $this->address                  = AU::get($data['address']);
        $this->format                   = AU::get($data['format']);
        $this->fields                   = AU::get($data['fields']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['topic']                = $this->topic;
        $object['address']              = $this->address;
        $object['format']               = $this->format;
        $object['fields']               = $this->fields;

        return $object;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return null|string
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param null|string $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

}