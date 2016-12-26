<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\ShoppingCartIntegration;
use App\Repositories\Doctrine\BaseRepository;

class ShoppingCartIntegrationRepository extends BaseRepository
{


    /**
     * @param   int     $id
     * @return  ShoppingCartIntegration|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}