<?php

namespace App\Models\WMS;


use App\Models\Shipments\Shipment;
use jamesvweston\Utilities\ArrayUtil AS AU;

class PickTote implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var PickInstruction
     */
    protected $pickInstruction;

    /**
     * @var Tote|null
     */
    protected $tote;

    /**
     * @var Shipment|null
     */
    protected $shipment;


    public function __construct($data = [])
    {
        $this->pickInstruction          = AU::get($data['pickInstruction']);
        $this->tote                     = AU::get($data['tote']);
        $this->shipment                 = AU::get($data['shipment']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['tote']                 = is_null($this->tote) ? null : $this->tote->jsonSerialize();
        $object['shipment']             = is_null($this->shipment) ? null : $this->shipment->jsonSerialize();

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
     * @return PickInstruction
     */
    public function getPickInstruction()
    {
        return $this->pickInstruction;
    }

    /**
     * @param PickInstruction $pickInstruction
     */
    public function setPickInstruction($pickInstruction)
    {
        $this->pickInstruction = $pickInstruction;
    }

    /**
     * @return Tote|null
     */
    public function getTote()
    {
        return $this->tote;
    }

    /**
     * @param Tote|null $tote
     */
    public function setTote($tote)
    {
        $this->tote = $tote;
    }

    /**
     * @return Shipment|null
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param Shipment|null $shipment
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
    }

}