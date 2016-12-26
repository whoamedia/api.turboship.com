<?php

namespace App\Repositories\Doctrine\Integrations;


use App\Models\Integrations\IntegratedWebHook;
use App\Repositories\Doctrine\BaseRepository;

class IntegratedWebHookRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  IntegratedWebHook|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}