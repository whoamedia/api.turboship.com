<?php

namespace App\Services\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyOrderLineItem implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * he amount available to fulfill.
     * This is the quantity - max(refunded_quantity, fulfilled_quantity) - pending_fulfilled_quantity - open_fulfilled_quantity.
     * @var int
     */
    protected $fulfillable_quantity;

    /**
     * Service provider who is doing the fulfillment.
     * Valid values are either "manual" or the name of the provider. eg: "amazon", "shipwire", etc.
     * @var string
     */
    protected $fulfillment_service;

    /**
     * How far along an order is in terms line items fulfilled.
     * Valid values are: fulfilled, null or partial.
     * @var string
     */
    protected $fulfillment_status;

    /**
     * @var int
     */
    protected $grams;

    /**
     * The price of the item before discounts have been applied.
     * @var float
     */
    protected $price;

    /**
     * The unique numeric identifier for the product in the fulfillment.
     * Can be null if the original product associated with the order is deleted at a later date
     * @var int|null
     */
    protected $product_id;

    /**
     * @var bool
     */
    protected $product_exists;

    /**
     * The number of products that were purchased.
     * @var int
     */
    protected $quantity;

    /**
     * States whether or not the fulfillment requires shipping
     * Values are: true or false.
     * @var bool
     */
    protected $requires_shipping;

    /**
     * A unique identifier of the item in the fulfillment.
     * @var string
     */
    protected $sku;

    /**
     * The title of the product.
     * @var string
     */
    protected $title;

    /**
     * The id of the product variant.
     * @var int
     */
    protected $variant_id;

    /**
     *  The title of the product variant.
     * @var string
     */
    protected $variant_title;

    /**
     * The name of the supplier of the item.
     * @var string
     */
    protected $vendor;

    /**
     * The name of the product variant.
     * @var string
     */
    protected $name;

    /**
     * States whether or not the line_item is a gift card
     * If so, the item is not taxed or considered for shipping charges.
     * @var string
     */
    protected $gift_card;

    /**
     * An array of custom information for an item that has been added to the cart.
     * Often used to provide product customization options
     * @var array
     */
    protected $properties;

    /**
     * States whether or not the product was taxable.
     * Values are: true or false.
     * @var bool
     */
    protected $taxable;

    /**
     * A list of tax_line objects, each of which details the taxes applicable to this line_item.
     * @var array
     */
    protected $tax_lines;

    /**
     * The total discount amount applied to this line item.
     * This value is not subtracted in the line item price.
     * @var float
     */
    protected $total_discount;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->fulfillable_quantity     = AU::get($data['fulfillable_quantity']);
        $this->fulfillment_service      = AU::get($data['fulfillment_service']);
        $this->fulfillment_status       = AU::get($data['fulfillment_status']);
        $this->grams                    = AU::get($data['grams']);
        $this->price                    = AU::get($data['price']);
        $this->product_id               = AU::get($data['product_id']);
        $this->product_exists           = AU::get($data['product_exists']);
        $this->quantity                 = AU::get($data['quantity']);
        $this->requires_shipping        = AU::get($data['requires_shipping']);
        $this->sku                      = AU::get($data['sku']);
        $this->title                    = AU::get($data['title']);
        $this->variant_id               = AU::get($data['variant_id']);
        $this->variant_title            = AU::get($data['variant_title']);
        $this->vendor                   = AU::get($data['vendor']);
        $this->name                     = AU::get($data['name']);
        $this->gift_card                = AU::get($data['gift_card']);
        $this->properties               = AU::get($data['properties']);
        $this->taxable                  = AU::get($data['taxable']);

        $this->tax_lines                = [];
        $tax_lines                      = AU::get($data['tax_lines']);
        foreach ($tax_lines AS $item)
            $this->tax_lines[]          = new ShopifyTaxLine($item);

        $this->total_discount           = AU::get($data['total_discount']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['fulfillable_quantity'] = $this->fulfillable_quantity;
        $object['fulfillment_service']  = $this->fulfillment_service;
        $object['fulfillment_status']   = $this->fulfillment_status;
        $object['grams']                = $this->grams;
        $object['price']                = $this->price;
        $object['product_id']           = $this->product_id;
        $object['product_exists']       = $this->product_exists;
        $object['quantity']             = $this->quantity;
        $object['requires_shipping']    = $this->requires_shipping;
        $object['sku']                  = $this->sku;
        $object['title']                = $this->title;
        $object['variant_id']           = $this->variant_id;
        $object['variant_title']        = $this->variant_title;
        $object['vendor']               = $this->vendor;
        $object['name']                 = $this->name;
        $object['gift_card']            = $this->gift_card;
        $object['properties']           = $this->properties;
        $object['taxable']              = $this->taxable;

        $object['tax_lines']            = [];
        foreach ($this->tax_lines AS $item)
            $object['tax_lines'][]      = $item->jsonSerialize();

        $object['total_discount']       = $this->total_discount;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;

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
     * @return int
     */
    public function getFulfillableQuantity()
    {
        return $this->fulfillable_quantity;
    }

    /**
     * @param int $fulfillable_quantity
     */
    public function setFulfillableQuantity($fulfillable_quantity)
    {
        $this->fulfillable_quantity = $fulfillable_quantity;
    }

    /**
     * @return string
     */
    public function getFulfillmentService()
    {
        return $this->fulfillment_service;
    }

    /**
     * @param string $fulfillment_service
     */
    public function setFulfillmentService($fulfillment_service)
    {
        $this->fulfillment_service = $fulfillment_service;
    }

    /**
     * @return string
     */
    public function getFulfillmentStatus()
    {
        return $this->fulfillment_status;
    }

    /**
     * @param string $fulfillment_status
     */
    public function setFulfillmentStatus($fulfillment_status)
    {
        $this->fulfillment_status = $fulfillment_status;
    }

    /**
     * @return int
     */
    public function getGrams()
    {
        return $this->grams;
    }

    /**
     * @param int $grams
     */
    public function setGrams($grams)
    {
        $this->grams = $grams;
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
     * @return int|null
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param int|null $product_id
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @return boolean
     */
    public function isProductExists()
    {
        return $this->product_exists;
    }

    /**
     * @param boolean $product_exists
     */
    public function setProductExists($product_exists)
    {
        $this->product_exists = $product_exists;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return boolean
     */
    public function isRequiresShipping()
    {
        return $this->requires_shipping;
    }

    /**
     * @param boolean $requires_shipping
     */
    public function setRequiresShipping($requires_shipping)
    {
        $this->requires_shipping = $requires_shipping;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
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
     * @return int
     */
    public function getVariantId()
    {
        return $this->variant_id;
    }

    /**
     * @param int $variant_id
     */
    public function setVariantId($variant_id)
    {
        $this->variant_id = $variant_id;
    }

    /**
     * @return string
     */
    public function getVariantTitle()
    {
        return $this->variant_title;
    }

    /**
     * @param string $variant_title
     */
    public function setVariantTitle($variant_title)
    {
        $this->variant_title = $variant_title;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
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
     * @return string
     */
    public function getGiftCard()
    {
        return $this->gift_card;
    }

    /**
     * @param string $gift_card
     */
    public function setGiftCard($gift_card)
    {
        $this->gift_card = $gift_card;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return boolean
     */
    public function isTaxable()
    {
        return $this->taxable;
    }

    /**
     * @param boolean $taxable
     */
    public function setTaxable($taxable)
    {
        $this->taxable = $taxable;
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
     * @return float
     */
    public function getTotalDiscount()
    {
        return $this->total_discount;
    }

    /**
     * @param float $total_discount
     */
    public function setTotalDiscount($total_discount)
    {
        $this->total_discount = $total_discount;
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

}