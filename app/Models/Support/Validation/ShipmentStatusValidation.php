<?php

namespace App\Models\Support\Validation;


use App\Models\Support\ShipmentStatus;
use App\Repositories\Doctrine\Support\ShipmentStatusRepository;
use App\Utilities\ShipmentStatusUtility;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipmentStatusValidation
{

    /**
     * @var ShipmentStatusRepository
     */
    private $shipmentStatusRepo;


    public function __construct()
    {
        $this->shipmentStatusRepo           = EntityManager::getRepository('App\Models\Support\ShipmentStatus');
    }

    /**
     * @param   int     $id
     * @return  ShipmentStatus
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $shipmentStatus                     = $this->shipmentStatusRepo->getOneById($id);

        if (is_null($shipmentStatus))
            throw new NotFoundHttpException('ShipmentStatus not found');

        return $shipmentStatus;
    }

    /**
     * @return  ShipmentStatus
     */
    public function getPendingInventoryReservation ()
    {
        return $this->idExists(ShipmentStatusUtility::PENDING_INVENTORY_RESERVATION);
    }

    /**
     * @return  ShipmentStatus
     */
    public function getPending ()
    {
        return $this->idExists(ShipmentStatusUtility::PENDING);
    }

    /**
     * @return  ShipmentStatus
     */
    public function getPartiallyShipped ()
    {
        return $this->idExists(ShipmentStatusUtility::PARTIALLY_SHIPPED);
    }

    /**
     * @return  ShipmentStatus
     */
    public function getFullyShipped ()
    {
        return $this->idExists(ShipmentStatusUtility::FULLY_SHIPPED);
    }

    /**
     * @return ShipmentStatus
     */
    public function getInsufficientInventory ()
    {
        return $this->idExists(ShipmentStatusUtility::INSUFFICIENT_INVENTORY);
    }

}