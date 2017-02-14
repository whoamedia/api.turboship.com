<?php

namespace App\Models\WMS\Validation;


use App\Models\WMS\Bin;
use App\Repositories\Doctrine\WMS\BinRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BinValidation
{

    /**
     * @var BinRepository
     */
    private $binRepo;


    public function __construct ()
    {
        $this->binRepo                  = EntityManager::getRepository('App\Models\WMS\Bin');
    }


    /**
     * @param   int     $id
     * @return  Bin
     */
    public function idExists ($id)
    {
        $bin                            = $this->binRepo->getOneById($id);
        if (is_null($bin))
            throw new NotFoundHttpException('Bin not found');

        return $bin;
    }

    /**
     * @param   int     $organizationId
     * @param   string  $barcode
     * @return  Bin
     */
    public function barCodeExists ($organizationId, $barcode)
    {
        $query          = [
            'organizationIds'       => $organizationId,
            'barCodes'              => $barcode,
        ];

        $results                        = $this->binRepo->where($query);

        if (sizeof($results) != 1)
            throw new NotFoundHttpException('Bin not found');

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

        $results                        = $this->binRepo->where($query);

        if (sizeof($results) != 0)
            throw new NotFoundHttpException('Bin barCode already exists');
    }

    /**
     * @param   Bin $bin
     * @return  bool
     */
    public function uniqueRackLocation (Bin $bin)
    {
        $query          = [
            'aisles'                => $bin->getAisle(),
            'sections'              => $bin->getSection(),
            'rows'                  => $bin->getRow(),
            'cols'                  => $bin->getCol()
        ];

        $results                    = $this->binRepo->where($query);

        if (sizeof($results) == 0)
            return true;


        foreach ($results AS $binCheck)
        {
            if ($binCheck->getId() != $bin->getId())
                throw new NotFoundHttpException('The Bin rack location already exists');
        }

        return true;
    }

}