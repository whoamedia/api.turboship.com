<?php

namespace App\Services\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Product implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
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
     * @var string
     */
    protected $product_type;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * A human-friendly unique string for the Product automatically generated from its title.
     * They are used by the Liquid templating language to refer to objects.
     * @var string
     */
    protected $handle;

    /**
     * @var string
     */
    protected $updated_at;

    /**
     * @var string
     */
    protected $published_at;

    /**
     * @var string|null
     */
    protected $template_suffix;

    /**
     * @var string
     */
    protected $published_scope;

    /**
     * @var string
     */
    protected $tags;

    /**
     * @var Variant[]
     */
    protected $variants;


    /**
     * Product constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->title                    = AU::get($data['title']);
        $this->body_html                = AU::get($data['body_html']);
        $this->vendor                   = AU::get($data['vendor']);
        $this->product_type             = AU::get($data['product_type']);
        $this->created_at               = AU::get($data['created_at']);
        $this->handle                   = AU::get($data['handle']);
        $this->updated_at               = AU::get($data['updated_at']);
        $this->published_at             = AU::get($data['published_at']);
        $this->template_suffix          = AU::get($data['template_suffix']);
        $this->published_scope          = AU::get($data['published_scope']);
        $this->tags                     = AU::get($data['tags']);

        $this->variants                 = [];
        $variants                       = AU::get($data['variants'], []);
        foreach ($variants AS $item)
        {
            $this->variants[]           = new Variant($item);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['title']                = $this->title;
        $object['body_html']            = $this->body_html;
        $object['vendor']               = $this->vendor;
        $object['product_type']         = $this->product_type;
        $object['created_at']           = $this->created_at;
        $object['handle']               = $this->handle;
        $object['updated_at']           = $this->updated_at;
        $object['published_at']         = $this->published_at;
        $object['template_suffix']      = $this->template_suffix;
        $object['published_scope']      = $this->published_scope;
        $object['tags']                 = $this->tags;
        $object['variants']             = [];

        foreach ($this->variants AS $variant)
        {
            $object['variants'][]       = $variant->jsonSerialize();
        }

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
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param string $published_at
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
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
     * @return Variant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param Variant[] $variants
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
    }

}