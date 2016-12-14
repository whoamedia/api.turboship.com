<?php

namespace App\Repositories\Doctrine\Locations;


use App\Models\Locations\SubdivisionType;
use App\Repositories\Doctrine\BaseRepository;

class SubdivisionTypeRepository extends BaseRepository
{

    /**
     * @param   int         $id
     * @return  SubdivisionType|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }
}