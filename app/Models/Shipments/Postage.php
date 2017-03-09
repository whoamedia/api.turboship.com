<?php

namespace App\Models\Shipments;


use jamesvweston\Utilities\ArrayUtil AS AU;

class Postage implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Rate
     */
    protected $rate;

    /**
     * @var string
     */
    protected $trackingNumber;

    /**
     * @var string
     */
    protected $labelPath;

    /**
     * @var string|null
     */
    protected $zplPath;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $voidedAt;

    /**
     * @var Shipment
     */
    protected $shipment;

    /**
     * @var string|null
     */
    protected $externalId;

    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();

        $this->rate                     = AU::get($data['rate']);
        $this->trackingNumber           = AU::get($data['trackingNumber']);
        $this->labelPath                = AU::get($data['labelPath']);
        $this->zplPath                  = AU::get($data['zplPath']);
        $this->shipment                 = AU::get($data['shipment']);
        $this->externalId               = AU::get($data['externalId']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['rate']                 = $this->rate->jsonSerialize();
        $object['trackingNumber']       = $this->trackingNumber;
        $object['labelPath']            = $this->labelPath;
        $object['externalId']           = $this->externalId;
        $object['createdAt']            = $this->createdAt;
        $object['voidedAt']             = $this->voidedAt;

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
     * @return Rate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param Rate $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }

    /**
     * @param string $trackingNumber
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;
    }

    /**
     * @return string
     */
    public function getLabelPath()
    {
        return $this->labelPath;
    }

    /**
     * @param string $labelPath
     */
    public function setLabelPath($labelPath)
    {
        $this->labelPath = $labelPath;
    }

    /**
     * @return null|string
     */
    public function getZplPath()
    {
        return $this->zplPath;
    }

    /**
     * @param null|string $zplPath
     */
    public function setZplPath($zplPath)
    {
        $this->zplPath = $zplPath;
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

    /**
     * @return \DateTime|null
     */
    public function getVoidedAt()
    {
        return $this->voidedAt;
    }

    /**
     * @param \DateTime|null $voidedAt
     */
    public function setVoidedAt($voidedAt)
    {
        $this->voidedAt = $voidedAt;
    }

    /**
     * @return Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param Shipment $shipment
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
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

}