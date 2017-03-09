<?php

namespace App\Models\WMS\Validation;


use App\Models\WMS\InventoryLocation;
use App\Repositories\Doctrine\WMS\InventoryLocationRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InventoryLocationValidation
{

    /**
     * @var InventoryLocationRepository
     */
    private $inventoryLocationRepo;


    public function __construct ()
    {
        $this->inventoryLocationRepo    = EntityManager::getRepository('App\Models\WMS\InventoryLocation');
    }


    /**
     * @param   int     $id
     * @return  InventoryLocation
     */
    public function idExists ($id)
    {
        $inventoryLocation              = $this->inventoryLocationRepo->getOneById($id);
        if (is_null($inventoryLocation))
            throw new NotFoundHttpException('InventoryLocation not found');

        return $inventoryLocation;
    }

}