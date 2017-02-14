<?php

namespace App\Models\WMS\Validation;


use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\WMS\VariantInventoryRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VariantInventoryValidation
{

    /**
     * @var VariantInventoryRepository
     */
    private $variantInventoryRepo;


    /**
     * VariantInventoryValidation constructor.
     * @param   VariantInventoryRepository|null $variantInventoryRepo
     */
    public function __construct ($variantInventoryRepo = null)
    {
        if (is_null($variantInventoryRepo))
            $this->variantInventoryRepo     = EntityManager::getRepository('App\Models\WMS\VariantInventory');
        else
            $this->variantInventoryRepo     = $variantInventoryRepo;
    }

    /**
     * @param   int     $id
     * @return  VariantInventory
     */
    public function idExists ($id)
    {
        $variantInventory                   = $this->variantInventoryRepo->getOneById($id);
        if (is_null($variantInventory))
            throw new NotFoundHttpException('VariantInventory not found');

        return $variantInventory;
    }

}