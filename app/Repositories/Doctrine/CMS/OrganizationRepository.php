<?php

namespace App\Repositories\Doctrine\CMS;


use App\Models\CMS\Organization;
use App\Repositories\Doctrine\BaseRepository;

class OrganizationRepository extends BaseRepository
{

    /**
     * @param   int     $id
     * @return  Organization|null
     */
    public function getOneById($id)
    {
        return $this->find($id);
    }

    /**
     * @param   string  $name
     * @return  Organization|null
     */
    public function getOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
    
}