<?php

namespace App\Integrations\Shopify;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyConfiguration
{

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $hostName;

    /**
     * @var string
     */
    protected $url;


    public function __construct($data = [])
    {
        $this->apiKey                   = AU::get($data['apiKey']);
        $this->password                 = AU::get($data['password']);
        $this->hostName                 = AU::get($data['hostName']);

        $this->setUrl();
    }


    private function setUrl ()
    {
        if (is_null($this->apiKey) || is_null($this->password) || is_null($this->hostName))
            return;

        $this->url                      = 'https://' . $this->apiKey . ':' . $this->password . '@' . $this->hostName . '.myshopify.com/admin';
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->setUrl();
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        $this->setUrl();
    }

    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->hostName;
    }

    /**
     * @param string $hostName
     */
    public function setHostName($hostName)
    {
        $this->hostName = $hostName;
        $this->setUrl();
    }

    /**
     * @return string
     */
    public function getUrl ()
    {
        return $this->url;
    }

}