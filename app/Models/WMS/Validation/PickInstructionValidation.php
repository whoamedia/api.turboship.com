<?php

namespace App\Models\WMS\Validation;


use App\Models\WMS\PickInstruction;
use App\Repositories\Doctrine\WMS\PickInstructionRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PickInstructionValidation
{

    /**
     * @var PickInstructionRepository
     */
    private $pickInstructionRepo;


    public function __construct ()
    {
        $this->pickInstructionRepo      = EntityManager::getRepository('App\Models\WMS\PickInstruction');
    }


    /**
     * @param   int     $id
     * @return  PickInstruction
     */
    public function idExists ($id)
    {
        $inventoryLocation              = $this->pickInstructionRepo->getOneById($id);
        if (is_null($inventoryLocation))
            throw new NotFoundHttpException('PickInstruction not found');

        return $inventoryLocation;
    }

}