<?php

namespace App\Models\Logs;


use App\Models\Integrations\IntegratedShoppingCart;
use jamesvweston\Utilities\ArrayUtil AS AU;

class ShopifyWebHookLog
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var IntegratedShoppingCart
     */
    protected $integratedShoppingCart;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var string
     */
    protected $incomingMessage;

    /**
     * @var string|null
     */
    protected $errorMessage;

    /**
     * @var bool
     */
    protected $verified;

    /**
     * @var bool
     */
    protected $success;

    /**
     * @var int|null
     */
    protected $entityId;

    /**
     * @var string|null
     */
    protected $externalId;

    /**
     * @var bool
     */
    protected $entityCreated;

    /**
     * @var string|null
     */
    protected $notes;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->integratedShoppingCart   = AU::get($data['integratedShoppingCart']);
        $this->topic                    = AU::get($data['topic']);
        $this->incomingMessage          = AU::get($data['incomingMessage']);
        $this->errorMessage             = AU::get($data['errorMessage']);
        $this->verified                 = AU::get($data['verified']);
        $this->success                  = AU::get($data['success']);
        $this->entityId                 = AU::get($data['entityId']);
        $this->entityCreated            = AU::get($data['entityCreated'], false);
        $this->externalId               = AU::get($data['externalId']);
        $this->notes                    = AU::get($data['notes']);
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
     * @return IntegratedShoppingCart
     */
    public function getIntegratedShoppingCart()
    {
        return $this->integratedShoppingCart;
    }

    /**
     * @param IntegratedShoppingCart $integratedShoppingCart
     */
    public function setIntegratedShoppingCart($integratedShoppingCart)
    {
        $this->integratedShoppingCart = $integratedShoppingCart;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getIncomingMessage()
    {
        return $this->incomingMessage;
    }

    /**
     * @param string $incomingMessage
     */
    public function setIncomingMessage($incomingMessage)
    {
        $this->incomingMessage = $incomingMessage;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param null|string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * @param boolean $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return int|null
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param int|null $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return boolean
     */
    public function isEntityCreated()
    {
        return $this->entityCreated;
    }

    /**
     * @param boolean $entityCreated
     */
    public function setEntityCreated($entityCreated)
    {
        $this->entityCreated = $entityCreated;
    }

    /**
     * @return null|string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param null|string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return null|string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    public function addNote ($note)
    {
        if (is_null($this->notes))
            $this->notes    = $note;
        else
            $this->notes    .= '       ' . $note;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

}