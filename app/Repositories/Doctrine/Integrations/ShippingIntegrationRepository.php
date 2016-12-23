<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\ShippingIntegration;
use App\Repositories\Doctrine\BaseRepository;

class ShippingIntegrationRepository extends BaseRepository
{


    /**
     * @param   int     $id
     * @return  ShippingIntegration|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}