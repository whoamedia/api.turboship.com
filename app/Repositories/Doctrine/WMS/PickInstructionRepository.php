<?php

namespace App\Repositories\Doctrine\WMS;


use App\Models\WMS\PickInstruction;
use App\Repositories\Doctrine\BaseRepository;

class PickInstructionRepository extends BaseRepository
{



    /**
     * @param   int         $id
     * @return  PickInstruction|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}