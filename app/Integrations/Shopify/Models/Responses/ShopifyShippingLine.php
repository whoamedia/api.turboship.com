<?php

namespace App\Integrations\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyShippingLine implements \JsonSerializable
{

    /**
     * A reference to the shipping method.
     * @var string
     */
    protected $code;

    /**
     * The price of this shipping method.
     * @var float
     */
    protected $price;

    /**
     * The source of the shipping method.
     * @var string
     */
    protected $source;

    /**
     * The title of the shipping method.
     * @var string
     */
    protected $title;

    /**
     * A list of tax_line objects, each of which details the taxes applicable to this shipping_line.
     * @var array
     */
    protected $tax_lines;

    /**
     * A reference to the carrier service that provided the rate
     * Present if the rate was computed by a third party carrier service; null otherwise.
     * @var string|null
     */
    protected $carrier_identifier;

    /**
     * A reference to the fulfillment service that is being requested for the shipping method.
     * Present if shipping method requires processing by a third party fulfillment service; null otherwise.
     * @var int|null
     */
    protected $requested_fulfillment_service_id;


    public function __construct($data = [])
    {
        $this->code                     = AU::get($data['code']);
        $this->price                    = AU::get($data['price']);
        $this->source                   = AU::get($data['source']);
        $this->title                    = AU::get($data['title']);
        $this->tax_lines                = AU::get($data['tax_lines']);
        $this->carrier_identifier       = AU::get($data['carrier_identifier']);
        $this->requested_fulfillment_service_id     = AU::get($data['requested_fulfillment_service_id']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['code']                 = $this->code;
        $object['price']                = $this->price;
        $object['source']               = $this->source;
        $object['title']                = $this->title;
        $object['tax_lines']            = $this->tax_lines;
        $object['carrier_identifier']   = $this->carrier_identifier;
        $object['requested_fulfillment_service_id'] = $this->requested_fulfillment_service_id;

        return $object;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getTaxLines()
    {
        return $this->tax_lines;
    }

    /**
     * @param array $tax_lines
     */
    public function setTaxLines($tax_lines)
    {
        $this->tax_lines = $tax_lines;
    }

    /**
     * @return null|string
     */
    public function getCarrierIdentifier()
    {
        return $this->carrier_identifier;
    }

    /**
     * @param null|string $carrier_identifier
     */
    public function setCarrierIdentifier($carrier_identifier)
    {
        $this->carrier_identifier = $carrier_identifier;
    }

    /**
     * @return int|null
     */
    public function getRequestedFulfillmentServiceId()
    {
        return $this->requested_fulfillment_service_id;
    }

    /**
     * @param int|null $requested_fulfillment_service_id
     */
    public function setRequestedFulfillmentServiceId($requested_fulfillment_service_id)
    {
        $this->requested_fulfillment_service_id = $requested_fulfillment_service_id;
    }

}