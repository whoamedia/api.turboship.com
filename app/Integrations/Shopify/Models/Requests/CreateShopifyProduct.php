<?php

namespace App\Integrations\Shopify\Models\Requests;


use App\Integrations\Shopify\Models\Responses\ShopifyProductImage;
use App\Integrations\Shopify\Models\Responses\ShopifyProductOption;
use App\Integrations\Shopify\Models\Responses\ShopifyVariant;
use jamesvweston\Utilities\ArrayUtil AS AU;

class CreateShopifyProduct implements \JsonSerializable
{

    /**
     * The name of the product.
     * In a shop's catalog, clicking on a product's title takes you to that product's page.
     * On a product's page, the product's title typically appears in a large font.
     * @var string
     */
    protected $title;

    /**
     * The description of the product, complete with HTML formatting.
     * @var string
     */
    protected $body_html;

    /**
     * @var string
     */
    protected $vendor;

    /**
     * A categorization that a product can be tagged with, commonly used for filtering and searching.
     * @var string
     */
    protected $product_type;

    /**
     * A human-friendly unique string for the Product automatically generated from its title.
     * They are used by the Liquid templating language to refer to objects.
     * @var string
     */
    protected $handle;

    /**
     * The suffix of the liquid template being used.
     * By default, the original template is called product.liquid, without any suffix.
     * Any additional templates will be: product.suffix.liquid.
     * @var string|null
     */
    protected $template_suffix;

    /**
     * The name of the product, to be used for SEO purposes.
     * This will generally be added to the <meta name='title'> tag.
     * @var string|null
     */
    protected $metafields_global_title_tag;

    /**
     * The description of the product, to be used for SEO purposes.
     * This will generally be added to the <meta name='description'> tag.
     * @var string|null
     */
    protected $metafields_global_description_tag;

    /**
     * The sales channels in which the product is visible.
     * @var string
     */
    protected $published_scope;

    /**
     * A categorization that a product can be tagged with, commonly used for filtering and searching.
     * Each comma-separated tag has a character limit of 255.
     * @var string
     */
    protected $tags;

    /**
     * A list of variant objects, each one representing a slightly different version of the product.
     *
     * @var ShopifyVariant[]
     */
    protected $variants;

    /**
     * Custom product property names like "Size", "Color", and "Material".
     * Products are based on permutations of these options.
     * A product may have a maximum of 3 options. 255 characters limit each.
     * @var ShopifyProductOption[]
     */
    protected $options;

    /**
     * A list of image objects, each one representing an image associated with the product.
     * To reorder variants, update the product with the variants in the desired order. The position attribute on the variant will be ignored.
     * @var ShopifyProductImage[]
     */
    protected $images;


    /**
     * Product constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->title                    = AU::get($data['title']);
        $this->body_html                = AU::get($data['body_html']);
        $this->vendor                   = AU::get($data['vendor']);
        $this->product_type             = AU::get($data['product_type']);
        $this->handle                   = AU::get($data['handle']);
        $this->template_suffix          = AU::get($data['template_suffix']);
        $this->metafields_global_title_tag = AU::get($data['metafields_global_title_tag']);
        $this->metafields_global_description_tag = AU::get($data['metafields_global_description_tag']);
        $this->published_scope          = AU::get($data['published_scope']);
        $this->tags                     = AU::get($data['tags']);

        $this->variants                 = [];
        $variants                       = AU::get($data['variants'], []);
        foreach ($variants AS $item)
        {
            $this->variants[]           = new ShopifyVariant($item);
        }

        $this->options                  = [];
        $options                        = AU::get($data['options'], []);
        if (AU::isArrays($options))
        {
            foreach ($options AS $item)
                $this->options[]        = new ShopifyProductOption($item);
        }

        $this->images                   = [];
        $images                         = AU::get($data['images'], []);
        foreach ($images AS $item)
        {
            $this->images[]             = new ShopifyProductImage($item);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['title']                = $this->title;
        $object['body_html']            = $this->body_html;
        $object['vendor']               = $this->vendor;
        $object['product_type']         = $this->product_type;
        $object['handle']               = $this->handle;
        $object['template_suffix']      = $this->template_suffix;
        $object['metafields_global_title_tag']  = $this->metafields_global_title_tag;
        $object['metafields_global_description_tag']    = $this->metafields_global_description_tag;
        $object['published_scope']      = $this->published_scope;
        $object['tags']                 = $this->tags;
        $object['variants']             = [];

        foreach ($this->variants AS $variant)
        {
            $object['variants'][]       = $variant->jsonSerialize();
        }

        $object['options']              = [];
        foreach ($this->options AS $option)
        {
            $object['options'][]        = $option->jsonSerialize();
        }

        $object['images']               = [];
        foreach ($this->images AS $image)
        {
            $object['images'][]         = $image->jsonSerialize();
        }

        return $object;
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
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->body_html;
    }

    /**
     * @param string $body_html
     */
    public function setBodyHtml($body_html)
    {
        $this->body_html = $body_html;
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
    public function getProductType()
    {
        return $this->product_type;
    }

    /**
     * @param string $product_type
     */
    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * @return null|string
     */
    public function getTemplateSuffix()
    {
        return $this->template_suffix;
    }

    /**
     * @param null|string $template_suffix
     */
    public function setTemplateSuffix($template_suffix)
    {
        $this->template_suffix = $template_suffix;
    }

    /**
     * @return null|string
     */
    public function getMetafieldsGlobalTitleTag()
    {
        return $this->metafields_global_title_tag;
    }

    /**
     * @param null|string $metafields_global_title_tag
     */
    public function setMetafieldsGlobalTitleTag($metafields_global_title_tag)
    {
        $this->metafields_global_title_tag = $metafields_global_title_tag;
    }

    /**
     * @return null|string
     */
    public function getMetafieldsGlobalDescriptionTag()
    {
        return $this->metafields_global_description_tag;
    }

    /**
     * @param null|string $metafields_global_description_tag
     */
    public function setMetafieldsGlobalDescriptionTag($metafields_global_description_tag)
    {
        $this->metafields_global_description_tag = $metafields_global_description_tag;
    }

    /**
     * @return string
     */
    public function getPublishedScope()
    {
        return $this->published_scope;
    }

    /**
     * @param string $published_scope
     */
    public function setPublishedScope($published_scope)
    {
        $this->published_scope = $published_scope;
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
     * @return ShopifyVariant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param ShopifyVariant[] $variants
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
    }

    /**
     * @return ShopifyProductOption[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param ShopifyProductOption[] $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return ShopifyProductImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ShopifyProductImage[] $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

}