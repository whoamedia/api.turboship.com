<?php

namespace App\Repositories\Doctrine\Logs;


use App\Models\Logs\ShopifyWebHookLog;
use App\Repositories\Doctrine\BaseRepository;

class ShopifyWebHookLogRepository extends BaseRepository
{


    /**
     * @param   int         $id
     * @return  ShopifyWebHookLog|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}