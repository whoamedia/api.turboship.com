<?php

namespace App\Repositories\Doctrine\WMS;


use App\Models\WMS\PrinterType;
use App\Repositories\Doctrine\BaseRepository;

class PrinterTypeRepository extends BaseRepository
{

    /**
     * @param   int         $id
     * @return  PrinterType|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}