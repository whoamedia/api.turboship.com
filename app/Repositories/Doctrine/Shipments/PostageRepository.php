<?php

namespace App\Repositories\Doctrine\Shipments;


use App\Models\Shipments\Postage;
use App\Repositories\Doctrine\BaseRepository;

class PostageRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  Postage|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}