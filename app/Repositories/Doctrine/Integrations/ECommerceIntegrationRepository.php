<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\ECommerceIntegration;
use App\Repositories\Doctrine\BaseRepository;

class ECommerceIntegrationRepository extends BaseRepository
{


    /**
     * @param   int     $id
     * @return  ECommerceIntegration|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}