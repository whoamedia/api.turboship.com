<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Shipper;
use App\Repositories\Doctrine\BaseRepository;

class ShipperRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  Shipper|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}