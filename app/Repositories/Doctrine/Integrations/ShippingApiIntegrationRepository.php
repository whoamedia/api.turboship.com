<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\ShippingApiIntegration;
use App\Repositories\Doctrine\BaseRepository;

class ShippingApiIntegrationRepository extends BaseRepository
{


    /**
     * @param   int     $id
     * @return  ShippingApiIntegration|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}