<?php

namespace App\Http\Controllers;


use App\Http\Requests\PickInstructions\CreatePickInstruction;
use App\Http\Requests\PickInstructions\ShowPickInstruction;
use App\Models\WMS\PickInstruction;
use App\Models\WMS\Validation\PickInstructionValidation;
use App\Repositories\Doctrine\WMS\PickInstructionRepository;
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