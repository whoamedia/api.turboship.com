<?php

namespace App\Services;


use App\Models\CMS\Staff;
use App\Models\Shipments\Shipment;
use App\Models\WMS\Cart;
use App\Models\WMS\CartPick;
use App\Models\WMS\PickTote;
use App\Models\WMS\Tote;
use App\Models\WMS\TotePick;
use App\Repositories\Doctrine\Shipments\ShipmentRepository;
use App\Repositories\Doctrine\WMS\PickToteRepository;
use App\Utilities\ShipmentStatusUtility;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PickInstructionService
{

    /**
     * @var PickToteRepository
     */
    private $pickToteRepo;

    /**
     * @var ShipmentRepository
     */
    protected $shipmentRepo;


    public function __construct()
    {
        $this->pickToteRepo             = EntityManager::getRepository('App\Models\WMS\PickTote');
        $this->shipmentRepo             = EntityManager::getRepository('App\Models\Shipments\Shipment');
    }


    /**
     * @param   Cart|null       $cart
     * @param   Tote[]          $totes
     * @param   Shipment[]      $shipments
     * @param   Staff           $staff
     * @param   Staff           $createdBy
     * @return  TotePick|CartPick
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
                $shipments[0]->setInPickInstruction(true);
                $this->shipmentRepo->save($shipments[0]);
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
                    $item->setInPickInstruction(true);
                    $this->shipmentRepo->save($item);
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
            /**
             * TODO: Verify the cart is not active in another PickInstruction
             */
        }

        for ($i = 0; $i < sizeof($totes); $i++)
        {
            /**
             * TODO: Verify the tote is not active in another PickInstruction
             */
            $pickTote                   = new PickTote();
            $pickTote->setTote($totes[$i]);

            if (!empty($shipments))
                $pickTote->setShipment($shipments[$i]);

            $pickInstruction->addPickTote($pickTote);
        }

        $pickInstruction->setIsAssigned(false);
        $pickInstruction->setOrganization($createdBy->getOrganization());
        $pickInstruction->setStaff($staff);
        $pickInstruction->setCreatedBy($createdBy);

        return $pickInstruction;
    }

    /**
     * @param   Shipment    $shipment
     * @return  bool
     */
    public function canAddShipmentToPick ($shipment)
    {
        if ($shipment->getStatus()->getId() != ShipmentStatusUtility::PENDING)
            throw new BadRequestHttpException('Shipment id ' . $shipment->getId() . ' cannot be added to a pick instruction because its status is not Pending');

        if ($shipment->getInPickInstruction())
            throw new BadRequestHttpException('Shipment id ' . $shipment->getId() . ' is already in another pick instruction');

        return true;
    }
    /**
     * @param   TotePick|CartPick     $pickInstruction
     * @return  TotePick|CartPick
     */
    public function assignShipments ($pickInstruction)
    {
        $requiredShipments              = 0;
        foreach ($pickInstruction->getPickTotes() AS $pickTote)
        {
            if (is_null($pickTote->getShipment()))
                $requiredShipments++;
        }

        $shipmentQuery                  = [
            'organizationIds'           => $pickInstruction->getCreatedBy()->getOrganization()->getId(),
            'statusIds'                 => ShipmentStatusUtility::PENDING,
            'inPickInstruction'         => false,
        ];
        //  Get the oldest shipment that hasn't been picked and hasn't been assigned to a user yet
        if ($requiredShipments == 1)
        {
            $shipmentQuery['orderBy']   = 'shipment.id';
            $shipmentQuery['direction'] = 'ASC';
            $shipmentQuery['limit']     = 1;

            $shipmentResults            = $this->shipmentRepo->where($shipmentQuery);
            if (sizeof($shipmentResults) == 0)
                throw new BadRequestHttpException('There are currently no shipments available to pick');

            $shipment                   = $shipmentResults[0];
            $shipment->setInPickInstruction(true);
            $this->shipmentRepo->save($shipment);
            foreach ($pickInstruction->getPickTotes() AS $pickTote)
            {
                if (is_null($pickTote->getShipment()))
                {
                    $pickTote->setShipment($shipment);
                    break;
                }
            }
            return $pickInstruction;
        }


        //  Cart picking. Use simple approach of getting oldest shipments first until we have time to improve logic
        $shipmentQuery['orderBy']   = 'shipment.id';
        $shipmentQuery['direction'] = 'ASC';
        $shipmentQuery['limit']     = $requiredShipments;

        $shipmentResults            = $this->shipmentRepo->where($shipmentQuery);

        if (sizeof($shipmentResults) == 0)
            throw new BadRequestHttpException('There are currently no shipments available to pick');
        else if (sizeof($shipmentResults) < $requiredShipments)
            throw new BadRequestHttpException('There are only ' . sizeof($shipmentResults) . ' available to pick. Reduce your number of totes');


        $pickTotes                  = $pickInstruction->getPickTotes();
        for ($i = 0; $i < sizeof($shipmentResults); $i++)
        {
            $shipmentResults[$i]->setInPickInstruction(true);
            $this->shipmentRepo->save($shipmentResults[$i]);
            $pickTotes[$i]->setShipment($shipmentResults[$i]);
        }

        return $pickInstruction;
    }


}