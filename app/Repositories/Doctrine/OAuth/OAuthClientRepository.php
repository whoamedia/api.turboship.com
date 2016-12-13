<?php

namespace App\Repositories\Doctrine\OAuth;


use App\Models\OAuth\OAuthClient;
use App\Repositories\Doctrine\BaseRepository;

class OAuthClientRepository extends BaseRepository
{

    /**
     * @param       string                     $id
     * @return      OAuthClient|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

}