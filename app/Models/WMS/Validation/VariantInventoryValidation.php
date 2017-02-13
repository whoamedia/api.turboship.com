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
     * @param   int     $organizationId
     * @param   string  $barcode
     * @return  VariantInventory
     */
    public function barCodeExists ($organizationId, $barcode)
    {
        $query          = [
            'organizationIds'       => $organizationId,
            'barCodes'              => $barcode,
        ];

        $results                        = $this->variantInventoryRepo->where($query);

        if (sizeof($results) != 1)
            throw new NotFoundHttpException('Variant inventory not found');

        return $results[0];
    }

    /**
     * @param   int     $organizationId
     * @param   string  $barcode
     */
    public function barCodeDoesNotExist ($organizationId, $barcode)
    {
        $query          = [
            'organizationIds'       => $organizationId,
            'barCodes'              => $barcode,
        ];

        $results                        = $this->variantInventoryRepo->where($query);

        if (sizeof($results) != 0)
            throw new NotFoundHttpException('barCode already exists');
    }
}