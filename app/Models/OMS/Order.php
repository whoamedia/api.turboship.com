<?php

namespace App\Models\OMS;


use App\Models\CMS\Client;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;

class Order implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var OrderSource
     */
    protected $source;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var OrderStatusHistory
     */
    protected $status;

    /**
     * @var ArrayCollection
     */
    protected $statusHistory;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * OrderStatusHistory constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->statusHistory            = new ArrayCollection();

        $this->externalId               = AU::get($data['externalId']);
        $this->source                   = AU::get($data['source']);
        $this->client                   = AU::get($data['client']);
        $this->status                   = AU::get($data['status']);
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['externalId']           = $this->externalId;
        $object['source']               = $this->source->jsonSerialize();
        $object['client']               = $this->client->jsonSerialize();
        $object['status']               = $this->status->jsonSerialize();

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
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return OrderSource
     */
    public function getSource ()
    {
        return $this->source;
    }

    /**
     * @param OrderSource $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return OrderStatusHistory
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param OrderStatus $status
     */
    public function addStatus ($status)
    {
        $orderStatusHistory             = new OrderStatusHistory();
        $orderStatusHistory->setOrder($this);
        $orderStatusHistory->setStatus($status);

        $this->status                   = $orderStatusHistory;
        $this->statusHistory->add($orderStatusHistory);
    }

    /**
     * @return OrderStatusHistory[]
     */
    public function getStatusHistory()
    {
        return $this->statusHistory->toArray();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


}