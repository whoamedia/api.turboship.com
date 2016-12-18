<?php

namespace App\Services\Shopify\Models\Responses;


class ProductImage
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
     * @var int
     */
    protected $position;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    /**
     * @var string
     */
    protected $src;

    /**
     * @var array
     */
    protected $variant_ids;
}