<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\Integration;
use App\Repositories\Doctrine\BaseRepository;

class IntegrationRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  Integration|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}