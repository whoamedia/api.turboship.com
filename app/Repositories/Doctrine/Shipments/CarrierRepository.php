<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Carrier;
use App\Repositories\Doctrine\BaseRepository;

class CarrierRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  Carrier|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}