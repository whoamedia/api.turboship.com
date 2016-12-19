<?php

namespace App\Repositories\Doctrine\OMS;


use App\Models\OMS\Product;
use App\Repositories\Doctrine\BaseRepository;

class ProductRepository extends BaseRepository
{

    /**
     * @param   int         $id
     * @return  Product|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}