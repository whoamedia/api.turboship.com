<?php

namespace App\Repositories\Doctrine\WMS;


use App\Models\WMS\Printer;
use App\Repositories\Doctrine\BaseRepository;

class PrinterRepository extends BaseRepository
{

    /**
     * @param   int         $id
     * @return  Printer|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}