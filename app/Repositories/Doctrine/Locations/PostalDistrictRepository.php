<?php

namespace App\Repositories\Doctrine\Locations;


use App\Models\Locations\PostalDistrict;
use App\Repositories\Doctrine\BaseRepository;

class PostalDistrictRepository extends BaseRepository
{

    /**
     * @param   int         $id
     * @return  PostalDistrict|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }
}