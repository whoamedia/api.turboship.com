<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\ShipmentAlgorithm;
use App\Repositories\Doctrine\BaseRepository;

class ShipmentAlgorithmRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  ShipmentAlgorithm|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}