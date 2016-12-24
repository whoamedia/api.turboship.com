<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Service;
use App\Repositories\Doctrine\BaseRepository;

class ServiceRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  Service|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}