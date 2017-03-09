<?php

namespace App\Http\Controllers;


use App\Http\Requests\PickInstructions\CreatePickInstruction;
use App\Http\Requests\PickInstructions\ShowPickInstruction;
use App\Models\CMS\Validation\StaffValidation;
use App\Models\Shipments\Validation\ShipmentValidation;
use App\Models\WMS\PickInstruction;
use App\Models\WMS\Validation\CartValidation;
use App\Models\WMS\Validation\PickInstructionValidation;
use App\Models\WMS\Validation\ToteValidation;
use App\Repositories\Doctrine\WMS\PickInstructionRepository;
use App\Services\PickInstructionService;
use Illuminate\Http\Request;
use EntityManager;

class PickInstructionController extends BaseAuthController
{

    /**
     * @var PickInstructionRepository
     */
    private $pickInstructionRepo;

    /**
     * @var PickInstructionValidation
     */
    private $pickInstructionValidation;


    public function __construct ()
    {
        $this->pickInstructionRepo      = EntityManager::getRepository('App\Models\WMS\PickInstruction');
        $this->pickInstructionValidation= new PickInstructionValidation();
    }
    
    public function index (Request $request)
    {

    }

    public function show (Request $request)
    {
        $id                             = $request->route('id');
        $pickInstruction                = $this->getPickInstructionFromRoute($id);
        return response($pickInstruction);
    }

    public function store (Request $request)
    {
        $createPickInstruction          = new CreatePickInstruction($request->input());
        $createPickInstruction->validate();
        $createPickInstruction->clean();

        $totes                          = [];
        $toteIds                        = $createPickInstruction->getToteIds();
        if (!is_null($toteIds))
        {
            $toteIds                    = explode(',', $toteIds);
            $toteValidation             = new ToteValidation();

            foreach ($toteIds AS $id)
                $totes[]                = $toteValidation->idExists($id);
        }

        $cart                           = null;
        if (!is_null($createPickInstruction->getCartId()))
        {
            $cartValidation             = new CartValidation();
            $cart                       = $cartValidation->idExists($createPickInstruction->getCartId());
        }

        $staff                          = null;
        if (!is_null($createPickInstruction->getStaffId()))
        {
            $staffValidation            = new StaffValidation();
            $staff                      = $staffValidation->idExists($createPickInstruction->getStaffId());
        }
        else
            $staff                      = parent::getAuthStaff();

        $shipments                      = [];
        $shipmentIds                    = $createPickInstruction->getShipmentIds();
        if (!is_null($shipmentIds))
        {
            $shipmentIds                = explode(',', $shipmentIds);
            $shipmentValidation         = new ShipmentValidation();
            foreach ($shipmentIds AS $id)
            {
                $shipmentIds[]          = $shipmentValidation->idExists($id);
            }
        }

        $createdBy                      = parent::getAuthStaff();

        dd($createPickInstruction->jsonSerialize());
        $pickInstructionService         = new PickInstructionService();
        $pickInstruction                = $pickInstructionService->buildPickInstructionObject($cart, $totes, $shipments, $staff, $createdBy);

        if ($pickInstruction->getIsAssigned())
        {
            $this->pickInstructionRepo->saveAndCommit($pickInstruction);
            return response($pickInstruction, 201);
        }

    }

    public function update (Request $request)
    {

    }

    /**
     * @param   int     $id
     * @return  PickInstruction
     */
    private function getPickInstructionFromRoute ($id)
    {
        $showPickInstruction            = new ShowPickInstruction();
        $showPickInstruction->setId($id);
        $showPickInstruction->validate();
        $showPickInstruction->clean();

        $pickInstruction                = $this->pickInstructionValidation->idExists($showPickInstruction->getId());
        return $pickInstruction;
    }
}