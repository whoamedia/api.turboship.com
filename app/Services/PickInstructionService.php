<?php

namespace App\Services;


use App\Models\CMS\Staff;
use App\Models\Shipments\Shipment;
use App\Models\WMS\Cart;
use App\Models\WMS\CartPick;
use App\Models\WMS\PickInstruction;
use App\Models\WMS\PickTote;
use App\Models\WMS\Tote;
use App\Models\WMS\TotePick;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Utilities\ShipmentStatusUtility;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PickInstructionService
{


    /**
     * @var ShipmentRepository
     */
    protected $shipmentRepo;


    public function __construct()
    {
        $this->shipmentRepo      = EntityManager::getRepository('App\Models\Shipments\Shipment');
    }


    /**
     * @param   Cart|null       $cart
     * @param   Tote[]          $totes
     * @param   Shipment[]      $shipments
     * @param   Staff           $staff
     * @param   Staff           $createdBy
     * @return  PickInstruction
     */
    public function buildPickInstructionObject ($cart, $totes, $shipments, $staff, $createdBy)
    {
        //  First identify points of failure. Why shouldn't we allow this pick instruction to be created?

        /**
         * If cart is null and totes is empty someone is creating a pick for another user. The cart and/or totes will be populated at a later time.
         * In this case shipments must be provided
         */
        if (is_null($cart) && empty($totes))
        {
            if (empty($shipments))
                throw new BadRequestHttpException('shipments must be provided when creating pick instructions for other users');

            if (sizeof($shipments) == 1)
            {
                $pickInstruction        = new TotePick();
                $pickTote               = new PickTote();
                $this->canAddShipmentToPick($shipments[0]);
                $pickTote->setShipment($shipments[0]);
                $pickInstruction->addPickTote($pickTote);
            }
            else
            {
                $pickInstruction        = new CartPick();
                foreach ($shipments AS $item)
                {
                    $pickTote           = new PickTote();
                    $this->canAddShipmentToPick($item);
                    $pickTote->setShipment($item);
                    $pickInstruction->addPickTote($pickTote);
                }
            }
            $pickInstruction->setIsAssigned(true);
            $pickInstruction->setOrganization($createdBy->getOrganization());
            $pickInstruction->setStaff($staff);
            $pickInstruction->setCreatedBy($createdBy);
            return $pickInstruction;
        }
        /**
         * If a cart is provided totes cannot be empty
         */
        else if (!is_null($cart) && empty($totes))
            throw new BadRequestHttpException('If cart picking totes must be supplied');
        /**
         * If a cart is not provided the number of totes must be equal to 1
         */
        else if (is_null($cart) && sizeof($totes) != 1)
            throw new BadRequestHttpException('If tote picking one and only one tote can be supplied');
        /**
         * The user is requesting a pick instruction to be created for them and specifying which shipments they want to pick
         */
        else if (!empty($totes) && !empty($shipments))
        {
            if (sizeof($totes) != sizeof($shipments))
                throw new BadRequestHttpException('The number of totes must be equal to the number of shipments');
        }

        /**
         * The user is requesting a pick instruction to be created for them
         */
        if (is_null($cart))
            $pickInstruction            = new TotePick();
        else
        {
            $pickInstruction            = new CartPick();
            $pickInstruction->setCart($cart);
        }

        for ($i = 0; $i < sizeof($totes); $i++)
        {
            $pickTote                   = new PickTote();
            $pickTote->setTote($totes[$i]);

            if (!empty($shipments))
                $pickTote->setShipment($shipments[$i]);

            $pickInstruction->addPickTote($pickTote);
        }

        return $pickInstruction;
    }

    /**
     * @param   Shipment    $shipment
     * @return  bool
     */
    public function canAddShipmentToPick ($shipment)
    {
        if ($shipment->getId() != ShipmentStatusUtility::PENDING)
            throw new BadRequestHttpException('Shipment id ' . $shipment->getId() . ' cannot be added to a pick instruction because its status is not Pending');

        return true;
    }
    /**
     * @param   TotePick|CartPick     $pickInstruction
     */
    public function assignShipments ($pickInstruction)
    {
        $totalShipments                 = sizeof($pickInstruction->getPickTotes());

        //  Get the oldest shipment that hasn't been picked and hasn't been assigned to a user yet
        if ($totalShipments == 1)
        {

        }
    }
}