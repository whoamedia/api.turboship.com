<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\IntegrationWebHook;
use App\Repositories\Doctrine\BaseRepository;

class IntegrationWebHookRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  IntegrationWebHook|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}