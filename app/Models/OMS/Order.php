<?php

namespace App\Models\OMS;


use App\Models\CMS\Client;
use App\Models\Locations\Address;
use App\Models\Locations\ProvidedAddress;
use App\Utilities\OrderStatusUtility;
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
     * @var Address|null
     */
    protected $toAddress;

    /**
     * @var ProvidedAddress
     */
    protected $providedAddress;

    /**
     * @var ProvidedAddress
     */
    protected $billingAddress;

    /**
     * @var OrderSource
     */
    protected $source;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var OrderStatus
     */
    protected $status;

    /**
     * @var ArrayCollection
     */
    protected $items;

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
        $this->items                    = new ArrayCollection();
        $this->statusHistory            = new ArrayCollection();

        $this->externalId               = AU::get($data['externalId']);
        $this->toAddress                = AU::get($data['toAddress']);
        $this->providedAddress          = AU::get($data['providedAddress'], new ProvidedAddress());
        $this->billingAddress           = AU::get($data['billingAddress'], new ProvidedAddress());
        $this->source                   = AU::get($data['source']);
        $this->client                   = AU::get($data['client']);
        $this->status                   = AU::get($data['status']);

        if (is_null($this->status))
        {
            /**
             * Add the created status but don't add it to the OrderStatusHistory
             */
            $orderStatusUtility         = new OrderStatusUtility();
            $this->status               = $orderStatusUtility->getCreated();
        }
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['externalId']           = $this->externalId;
        $object['toAddress']            = is_null($this->toAddress) ? NULL : $this->toAddress->jsonSerialize();
        $object['providedAddress']      = $this->providedAddress->jsonSerialize();
        $object['source']               = $this->source->jsonSerialize();
        $object['client']               = $this->client->jsonSerialize();
        $object['status']               = $this->status->jsonSerialize();

        $object['items']                = [];
        foreach ($this->getItems() AS $item)
        {
            $object['items'][]          = $item->jsonSerialize();
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
     * @return Address|null
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * @param Address|null $toAddress
     */
    public function setToAddress($toAddress)
    {
        $this->toAddress = $toAddress;
    }

    /**
     * @return ProvidedAddress
     */
    public function getProvidedAddress()
    {
        return $this->providedAddress;
    }

    /**
     * @param ProvidedAddress $providedAddress
     */
    public function setProvidedAddress($providedAddress)
    {
        $this->providedAddress = $providedAddress;
    }

    /**
     * @return ProvidedAddress
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param ProvidedAddress $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
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
     * @return OrderItem[]
     */
    public function getItems ()
    {
        return $this->items->toArray();
    }

    /**
     * @param OrderItem $item
     */
    public function addItem (OrderItem $item)
    {
        $item->setOrder($this);
        $this->items->add($item);
    }

    /**
     * @return OrderStatus
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
        $this->status                   = $status;

        $orderStatusHistory             = new OrderStatusHistory();
        $orderStatusHistory->setOrder($this);
        $orderStatusHistory->setStatus($status);

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