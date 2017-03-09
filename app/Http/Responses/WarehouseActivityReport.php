<?php

namespace App\Http\Responses;


use jamesvweston\Utilities\ArrayUtil AS AU;

class WarehouseActivityReport implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $pendingShipments;

    /**
     * @var int
     */
    protected $rushedShipments;

    /**
     * @var int
     */
    protected $assignedShipments;

    /**
     * @var int
     */
    protected $pulledShipments;

    /**
     * @var int
     */
    protected $received;


    public function __construct($data = [])
    {
        $this->pendingShipments         = AU::get($data['pendingShipments'], 0);
        $this->rushedShipments          = AU::get($data['rushedShipments'], 0);
        $this->assignedShipments        = AU::get($data['assignedShipments'], 0);
        $this->pulledShipments          = AU::get($data['pulledShipments'], 0);
        $this->received                 = AU::get($data['received'], 0);
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['pendingShipments']     = $this->pendingShipments;
        $object['rushedShipments']      = $this->rushedShipments;
        $object['assignedShipments']    = $this->assignedShipments;
        $object['pulledShipments']      = $this->pulledShipments;
        $object['received']             = $this->received;

        return $object;
    }

    /**
     * @return int
     */
    public function getPendingShipments()
    {
        return $this->pendingShipments;
    }

    /**
     * @param int $pendingShipments
     */
    public function setPendingShipments($pendingShipments)
    {
        $this->pendingShipments = $pendingShipments;
    }

    /**
     * @return int
     */
    public function getRushedShipments()
    {
        return $this->rushedShipments;
    }

    /**
     * @param int $rushedShipments
     */
    public function setRushedShipments($rushedShipments)
    {
        $this->rushedShipments = $rushedShipments;
    }

    /**
     * @return int
     */
    public function getAssignedShipments()
    {
        return $this->assignedShipments;
    }

    /**
     * @param int $assignedShipments
     */
    public function setAssignedShipments($assignedShipments)
    {
        $this->assignedShipments = $assignedShipments;
    }

    /**
     * @return int
     */
    public function getPulledShipments()
    {
        return $this->pulledShipments;
    }

    /**
     * @param int $pulledShipments
     */
    public function setPulledShipments($pulledShipments)
    {
        $this->pulledShipments = $pulledShipments;
    }

    /**
     * @return int
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * @param int $received
     */
    public function setReceived($received)
    {
        $this->received = $received;
    }

}