<?php

namespace App\Services\Shopify\Models\Responses;


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
    protected $accepts_marketing;

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
        $this->note                     = AU::get($data['note']);
        $this->orders_count             = AU::get($data['orders_count']);
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