<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\ShipmentItem;
use App\Repositories\Doctrine\BaseRepository;

class ShipmentItemRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  ShipmentItem|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}