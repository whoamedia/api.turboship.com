<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\IntegratedShoppingCart;
use App\Repositories\Doctrine\BaseRepository;

class IntegratedShoppingCartRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  IntegratedShoppingCart|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}