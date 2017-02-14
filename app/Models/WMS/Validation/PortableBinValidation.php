<?php

namespace App\Models\WMS\Validation;


use App\Models\WMS\PortableBin;
use App\Repositories\Doctrine\WMS\PortableBinRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PortableBinValidation
{

    /**
     * @var PortableBinRepository
     */
    private $portableBinRepo;


    public function __construct ()
    {
        $this->portableBinRepo          = EntityManager::getRepository('App\Models\WMS\PortableBin');
    }


    /**
     * @param   int     $id
     * @return  PortableBin
     */
    public function idExists ($id)
    {
        $bin                            = $this->portableBinRepo->getOneById($id);
        if (is_null($bin))
            throw new NotFoundHttpException('PortableBin not found');

        return $bin;
    }

    /**
     * @param   int     $organizationId
     * @param   string  $barcode
     * @return  PortableBin
     */
    public function barCodeExists ($organizationId, $barcode)
    {
        $query          = [
            'organizationIds'       => $organizationId,
            'barCodes'              => $barcode,
        ];

        $results                        = $this->portableBinRepo->where($query);

        if (sizeof($results) != 1)
            throw new NotFoundHttpException('PortableBin not found');

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

        $results                        = $this->portableBinRepo->where($query);

        if (sizeof($results) != 0)
            throw new NotFoundHttpException('PortableBin barCode already exists');
    }

}