<?php

namespace App\Models\OAuth;


use jamesvweston\Utilities\ArrayUtil AS AU;
use Illuminate\Support\Str;

class OAuthClient
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->id                   = AU::get($data['id'], Str::random());
        $this->name                 = AU::get($data['name']);
        $this->secret               = AU::get($data['secret'], Str::random(32));
        $this->vendor               = AU::get($data['vendor']);
        $this->createdAt            = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}