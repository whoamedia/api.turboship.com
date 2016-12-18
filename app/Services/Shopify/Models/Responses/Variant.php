<?php

namespace App\Services\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Variant implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $product_id;

    /**
     * @var string
     */
    protected $title;

    /**
     * The price of the product variant.
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $sku;

    /**
     * The order of the product variant in the list of product variants.
     * 1 is the first position.
     * @var int
     */
    protected $position;

    /**
     * The weight of the product variant in grams.
     * @var int
     */
    protected $grams;

    /**
     * Specifies whether or not customers are allowed to place an order for a product variant when it's out of stock.
     * Valid values are: deny (default), continue
     * @var string
     */
    protected $inventory_policy;

    /**
     * The competitors prices for the same item.
     * @var string|null
     */
    protected $compare_at_price;

    /**
     * Service which is doing the fulfillment. Possible values are manual or the handle of a FulfillmentService.
     * @var string
     */
    protected $fulfillment_service;

    /**
     * Specifies whether or not Shopify tracks the number of items in stock for this product variant.
     * Valid values are: blank, shopify
     * @var string
     */
    protected $inventory_management;

    /**
     * @var string|null
     */
    protected $option1;

    /**
     * @var string|null
     */
    protected $option2;

    /**
     * @var string|null
     */
    protected $option3;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    /**
     * Specifies whether or not a tax is charged when the product variant is sold.
     * @var bool
     */
    protected $taxable;

    /**
     * The barcode, UPC or ISBN number for the product.
     * @var string
     */
    protected $barcode;

    /**
     * The unique numeric identifier for a product's image.
     * Image must be associated to the same product as the variant.
     * @var int|null
     */
    protected $image_id;

    /**
     * The number of items in stock for this product variant.
     * @var int
     */
    protected $inventory_quantity;

    /**
     * The weight of the product variant in the unit system specified with weight_unit.
     * @var float
     */
    protected $weight;

    /**
     * The unit system that the product variant's weight is measure in.
     * The weight_unit can be either "g", "kg, "oz", or "lb".
     * @var string
     */
    protected $weight_unit;

    /**
     * The original stock level the client believes the product variant has.
     * This should be sent to avoid a race condition when the item being adjusted is simultaneously sold online.
     * @var int
     */
    protected $old_inventory_quantity;

    /**
     * Instead of sending a new and old value for inventory an adjustment value can be sent.
     * If an adjustment value is sent it will take priority.
     * @var int|null
     */
    protected $inventory_quantity_adjustment;

    /**
     * Specifies whether or not a customer needs to provide a shipping address when placing an order for this product variant.
     * Valid values are: true, false
     * @var bool
     */
    protected $requires_shipping;


    /**
     * Variant constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->product_id               = AU::get($data['product_id']);
        $this->title                    = AU::get($data['title']);
        $this->setPrice(AU::get($data['price']));
        $this->sku                      = AU::get($data['sku']);
        $this->position                 = AU::get($data['position']);
        $this->grams                    = AU::get($data['grams']);
        $this->inventory_policy         = AU::get($data['inventory_policy'], 'deny');
        $this->compare_at_price         = AU::get($data['compare_at_price']);
        $this->fulfillment_service      = AU::get($data['fulfillment_service']);
        $this->inventory_management     = AU::get($data['inventory_management']);
        $this->option1                  = AU::get($data['option1']);
        $this->option2                  = AU::get($data['option2']);
        $this->option3                  = AU::get($data['option3']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
        $this->taxable                  = AU::get($data['taxable']);
        $this->barcode                  = AU::get($data['barcode']);
        $this->image_id                 = AU::get($data['image_id']);
        $this->inventory_quantity       = AU::get($data['inventory_quantity']);
        $this->weight                   = AU::get($data['weight']);
        $this->weight_unit              = AU::get($data['weight_unit']);
        $this->old_inventory_quantity   = AU::get($data['old_inventory_quantity']);
        $this->inventory_quantity_adjustment = AU::get($data['inventory_quantity_adjustment']);
        $this->requires_shipping        = AU::get($data['requires_shipping']);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['product_id']           = $this->product_id;
        $object['title']                = $this->title;
        $object['price']                = $this->price;
        $object['sku']                  = $this->sku;
        $object['position']             = $this->position;
        $object['grams']                = $this->grams;
        $object['inventory_policy']     = $this->inventory_policy;
        $object['compare_at_price']     = $this->compare_at_price;
        $object['fulfillment_service']  = $this->fulfillment_service;
        $object['inventory_management'] = $this->inventory_management;
        $object['option1']              = $this->option1;
        $object['option2']              = $this->option2;
        $object['option3']              = $this->option3;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;
        $object['taxable']              = $this->taxable;
        $object['barcode']              = $this->barcode;
        $object['image_id']             = $this->image_id;
        $object['inventory_quantity']   = $this->inventory_quantity;
        $object['weight']               = $this->weight;
        $object['weight_unit']          = $this->weight_unit;
        $object['old_inventory_quantity'] = $this->old_inventory_quantity;
        $object['inventory_quantity_adjustment'] = $this->inventory_quantity_adjustment;
        $object['requires_shipping']    = $this->requires_shipping;

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
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param int $product_id
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
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
        $this->price = is_null($price) ? null : (float)$price;
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
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
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
     * @return string
     */
    public function getInventoryPolicy()
    {
        return $this->inventory_policy;
    }

    /**
     * @param string $inventory_policy
     */
    public function setInventoryPolicy($inventory_policy)
    {
        $this->inventory_policy = $inventory_policy;
    }

    /**
     * @return null|string
     */
    public function getCompareAtPrice()
    {
        return $this->compare_at_price;
    }

    /**
     * @param null|string $compare_at_price
     */
    public function setCompareAtPrice($compare_at_price)
    {
        $this->compare_at_price = $compare_at_price;
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
    public function getInventoryManagement()
    {
        return $this->inventory_management;
    }

    /**
     * @param string $inventory_management
     */
    public function setInventoryManagement($inventory_management)
    {
        $this->inventory_management = $inventory_management;
    }

    /**
     * @return null|string
     */
    public function getOption1()
    {
        return $this->option1;
    }

    /**
     * @param null|string $option1
     */
    public function setOption1($option1)
    {
        $this->option1 = $option1;
    }

    /**
     * @return null|string
     */
    public function getOption2()
    {
        return $this->option2;
    }

    /**
     * @param null|string $option2
     */
    public function setOption2($option2)
    {
        $this->option2 = $option2;
    }

    /**
     * @return null|string
     */
    public function getOption3()
    {
        return $this->option3;
    }

    /**
     * @param null|string $option3
     */
    public function setOption3($option3)
    {
        $this->option3 = $option3;
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
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return int|null
     */
    public function getImageId()
    {
        return $this->image_id;
    }

    /**
     * @param int|null $image_id
     */
    public function setImageId($image_id)
    {
        $this->image_id = $image_id;
    }

    /**
     * @return int
     */
    public function getInventoryQuantity()
    {
        return $this->inventory_quantity;
    }

    /**
     * @param int $inventory_quantity
     */
    public function setInventoryQuantity($inventory_quantity)
    {
        $this->inventory_quantity = $inventory_quantity;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weight_unit;
    }

    /**
     * @param string $weight_unit
     */
    public function setWeightUnit($weight_unit)
    {
        $this->weight_unit = $weight_unit;
    }

    /**
     * @return int
     */
    public function getOldInventoryQuantity()
    {
        return $this->old_inventory_quantity;
    }

    /**
     * @param int $old_inventory_quantity
     */
    public function setOldInventoryQuantity($old_inventory_quantity)
    {
        $this->old_inventory_quantity = $old_inventory_quantity;
    }

    /**
     * @return int|null
     */
    public function getInventoryQuantityAdjustment()
    {
        return $this->inventory_quantity_adjustment;
    }

    /**
     * @param int|null $inventory_quantity_adjustment
     */
    public function setInventoryQuantityAdjustment($inventory_quantity_adjustment)
    {
        $this->inventory_quantity_adjustment = $inventory_quantity_adjustment;
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

}