<?php

namespace App\Services\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyTaxLine implements \JsonSerializable
{

    /**
     * The amount of tax to be charged.
     * @var float
     */
    protected $price;

    /**
     * The rate of tax to be applied.
     * @var float
     */
    protected $rate;

    /**
     * The name of the tax.
     * @var string
     */
    protected $title;


    public function __construct($data = [])
    {
        $this->price                    = AU::get($data['price']);
        $this->rate                     = AU::get($data['rate']);
        $this->title                    = AU::get($data['title']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['price']                = $this->price;
        $object['rate']                 = $this->rate;
        $object['title']                = $this->title;

        return $object;
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
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
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

}