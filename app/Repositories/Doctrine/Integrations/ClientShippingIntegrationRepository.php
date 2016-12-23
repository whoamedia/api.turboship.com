<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\ClientShippingIntegration;
use App\Repositories\Doctrine\BaseRepository;

class ClientShippingIntegrationRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  ClientShippingIntegration|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}