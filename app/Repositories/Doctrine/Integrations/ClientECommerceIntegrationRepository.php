<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\ClientECommerceIntegration;
use App\Repositories\Doctrine\BaseRepository;

class ClientECommerceIntegrationRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  ClientECommerceIntegration|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}