<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Rate;
use App\Repositories\Doctrine\BaseRepository;

class RateRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  Rate|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}