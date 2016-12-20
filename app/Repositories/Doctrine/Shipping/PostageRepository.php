<?php

namespace App\Repositories\Doctrine\Shipping;


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