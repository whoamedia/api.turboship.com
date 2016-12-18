<?php

namespace App\Services\Shopify\Models\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyClientDetail implements \JsonSerializable
{

    /**
     * @var string|null
     */
    protected $accept_language;

    /**
     * @var string
     */
    protected $browser_ip;

    /**
     * @var string
     */
    protected $session_hash;

    /**
     * @var string
     */
    protected $user_agent;

    /**
     * @var int|null
     */
    protected $browser_height;

    /**
     * @var int|null
     */
    protected $browser_width;


    public function __construct($data = [])
    {
        $this->accept_language          = AU::get($data['accept_language']);
        $this->browser_ip               = AU::get($data['browser_ip']);
        $this->session_hash             = AU::get($data['session_hash']);
        $this->user_agent               = AU::get($data['user_agent']);
        $this->browser_height           = AU::get($data['browser_height']);
        $this->browser_width            = AU::get($data['browser_width']);
    }

    /**
     * @return  array
     */
    public function jsonSerialize()
    {
        $object['accept_language']      = $this->accept_language;
        $object['browser_ip']           = $this->browser_ip;
        $object['session_hash']         = $this->session_hash;
        $object['user_agent']           = $this->user_agent;
        $object['browser_height']       = $this->browser_height;
        $object['browser_width']        = $this->browser_width;

        return $object;
    }

    /**
     * @return null|string
     */
    public function getAcceptLanguage()
    {
        return $this->accept_language;
    }

    /**
     * @param null|string $accept_language
     */
    public function setAcceptLanguage($accept_language)
    {
        $this->accept_language = $accept_language;
    }

    /**
     * @return string
     */
    public function getBrowserIp()
    {
        return $this->browser_ip;
    }

    /**
     * @param string $browser_ip
     */
    public function setBrowserIp($browser_ip)
    {
        $this->browser_ip = $browser_ip;
    }

    /**
     * @return string
     */
    public function getSessionHash()
    {
        return $this->session_hash;
    }

    /**
     * @param string $session_hash
     */
    public function setSessionHash($session_hash)
    {
        $this->session_hash = $session_hash;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * @param string $user_agent
     */
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;
    }

    /**
     * @return int|null
     */
    public function getBrowserHeight()
    {
        return $this->browser_height;
    }

    /**
     * @param int|null $browser_height
     */
    public function setBrowserHeight($browser_height)
    {
        $this->browser_height = $browser_height;
    }

    /**
     * @return int|null
     */
    public function getBrowserWidth()
    {
        return $this->browser_width;
    }

    /**
     * @param int|null $browser_width
     */
    public function setBrowserWidth($browser_width)
    {
        $this->browser_width = $browser_width;
    }

}