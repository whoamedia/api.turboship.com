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
     * @var Tote
     */
    protected $tote;

    /**
     * @var Shipment
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
        $object['tote']                 = $this->tote->jsonSerialize();
        $object['shipment']             = $this->shipment->jsonSerialize();

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
     * @return Tote
     */
    public function getTote()
    {
        return $this->tote;
    }

    /**
     * @param Tote $tote
     */
    public function setTote($tote)
    {
        $this->tote = $tote;
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

}