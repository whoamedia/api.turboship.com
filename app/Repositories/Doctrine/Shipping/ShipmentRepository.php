<?php

namespace App\Repositories\Doctrine\Shipping;


use App\Models\Shipments\Shipment;
use App\Repositories\Doctrine\BaseRepository;

class ShipmentRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  Shipment|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}