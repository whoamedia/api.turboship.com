<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\ShippingContainer;
use App\Repositories\Doctrine\BaseRepository;

class ShippingContainerRepository extends BaseRepository
{



    /**
     * @param   int     $id
     * @return  ShippingContainer|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}