<?php

namespace App\Repositories\Doctrine\Locations;


use App\Models\Locations\PostalDistrictSubdivision;
use App\Repositories\Doctrine\BaseRepository;

class PostalDistrictSubdivisionRepository extends BaseRepository
{

    /**
     * @param   int         $id
     * @return  PostalDistrictSubdivision|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }
}