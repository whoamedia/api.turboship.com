<?php

namespace App\Integrations\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyCustomer implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var bool
     */
    protected $verified_email;

    /**
     * @var string|null
     */
    protected $multipass_identifier;

    /**
     * @var bool
     */
    protected $accepts_marketing;

    /**
     * @var int|null
     */
    protected $last_order_id;

    /**
     * @var ShopifyAddress
     */
    protected $default_address;

    /**
     * @var string|null
     */
    protected $last_order_name;

    /**
     * @var string|null
     */
    protected $note;

    /**
     * @var int
     */
    protected $orders_count;

    /**
     * @var string|null
     */
    protected $state;

    /**
     * @var float
     */
    protected $total_spent;

    /**
     * @var bool
     */
    protected $tax_exempt;

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
    protected $tags;

    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->first_name               = AU::get($data['first_name']);
        $this->last_name                = AU::get($data['last_name']);
        $this->email                    = AU::get($data['email']);
        $this->accepts_marketing        = AU::get($data['accepts_marketing']);
        $this->verified_email           = AU::get($data['verified_email']);
        $this->multipass_identifier     = AU::get($data['multipass_identifier']);
        $this->last_order_id            = AU::get($data['last_order_id']);
        $this->last_order_name          = AU::get($data['last_order_name']);

        $this->default_address          = AU::get($data['default_address']);
        if (!is_null($this->default_address))
            $this->default_address      = new ShopifyAddress($this->default_address);

        $this->note                     = AU::get($data['note']);
        $this->orders_count             = AU::get($data['orders_count']);
        $this->tax_exempt               = AU::get($data['tax_exempt']);
        $this->state                    = AU::get($data['state']);
        $this->total_spent              = AU::get($data['total_spent']);
        $this->created_at               = AU::get($data['created_at']);
        $this->updated_at               = AU::get($data['updated_at']);
        $this->tags                     = AU::get($data['tags']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        //
        //
        $object['id']                   = $this->id;
        $object['first_name']           = $this->first_name;
        $object['last_name']            = $this->last_name;
        $object['email']                = $this->email;
        $object['accepts_marketing']    = $this->accepts_marketing;
        $object['note']                 = $this->note;
        $object['orders_count']         = $this->orders_count;
        $object['state']                = $this->state;
        $object['total_spent']          = $this->total_spent;
        $object['created_at']           = $this->created_at;
        $object['updated_at']           = $this->updated_at;
        $object['tags']                 = $this->tags;
        $object['verified_email']       = $this->verified_email;
        $object['multipass_identifier'] = $this->multipass_identifier;
        $object['last_order_id']        = $this->last_order_id;
        $object['default_address']      = is_null($this->default_address) ? null : $this->default_address->jsonSerialize();
        $object['last_order_name']      = $this->last_order_name;
        $object['tax_exempt']           = $this->tax_exempt;

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
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return boolean
     */
    public function isVerifiedEmail()
    {
        return $this->verified_email;
    }

    /**
     * @param boolean $verified_email
     */
    public function setVerifiedEmail($verified_email)
    {
        $this->verified_email = $verified_email;
    }

    /**
     * @return null|string
     */
    public function getMultipassIdentifier()
    {
        return $this->multipass_identifier;
    }

    /**
     * @param null|string $multipass_identifier
     */
    public function setMultipassIdentifier($multipass_identifier)
    {
        $this->multipass_identifier = $multipass_identifier;
    }

    /**
     * @return boolean
     */
    public function isAcceptsMarketing()
    {
        return $this->accepts_marketing;
    }

    /**
     * @param boolean $accepts_marketing
     */
    public function setAcceptsMarketing($accepts_marketing)
    {
        $this->accepts_marketing = $accepts_marketing;
    }

    /**
     * @return int|null
     */
    public function getLastOrderId()
    {
        return $this->last_order_id;
    }

    /**
     * @param int|null $last_order_id
     */
    public function setLastOrderId($last_order_id)
    {
        $this->last_order_id = $last_order_id;
    }

    /**
     * @return ShopifyAddress
     */
    public function getDefaultAddress()
    {
        return $this->default_address;
    }

    /**
     * @param ShopifyAddress $default_address
     */
    public function setDefaultAddress($default_address)
    {
        $this->default_address = $default_address;
    }

    /**
     * @return null|string
     */
    public function getLastOrderName()
    {
        return $this->last_order_name;
    }

    /**
     * @param null|string $last_order_name
     */
    public function setLastOrderName($last_order_name)
    {
        $this->last_order_name = $last_order_name;
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
     * @return int
     */
    public function getOrdersCount()
    {
        return $this->orders_count;
    }

    /**
     * @param int $orders_count
     */
    public function setOrdersCount($orders_count)
    {
        $this->orders_count = $orders_count;
    }

    /**
     * @return null|string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param null|string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return float
     */
    public function getTotalSpent()
    {
        return $this->total_spent;
    }

    /**
     * @param float $total_spent
     */
    public function setTotalSpent($total_spent)
    {
        $this->total_spent = $total_spent;
    }

    /**
     * @return boolean
     */
    public function isTaxExempt()
    {
        return $this->tax_exempt;
    }

    /**
     * @param boolean $tax_exempt
     */
    public function setTaxExempt($tax_exempt)
    {
        $this->tax_exempt = $tax_exempt;
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

}