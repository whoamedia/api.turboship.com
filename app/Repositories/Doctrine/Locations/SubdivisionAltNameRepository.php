<?php

namespace App\Repositories\Doctrine\Locations;


use App\Models\Locations\SubdivisionAltName;
use App\Repositories\Doctrine\BaseRepository;

class SubdivisionAltNameRepository extends BaseRepository
{

    /**
     * @param   int         $id
     * @return  SubdivisionAltName|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }
}