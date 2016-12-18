<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\IntegrationCredential;
use App\Repositories\Doctrine\BaseRepository;

class IntegrationCredentialRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  IntegrationCredential|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}