<?php

namespace App\Models\WMS\Validation;


use App\Models\WMS\Tote;
use App\Repositories\Doctrine\WMS\ToteRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToteValidation
{

    /**
     * @var ToteRepository
     */
    private $toteRepo;


    public function __construct ()
    {
        $this->toteRepo                 = EntityManager::getRepository('App\Models\WMS\Tote');
    }


    /**
     * @param   int     $id
     * @return  Tote
     */
    public function idExists ($id)
    {
        $tote                           = $this->toteRepo->getOneById($id);
        if (is_null($tote))
            throw new NotFoundHttpException('Tote not found');

        return $tote;
    }

    /**
     * @param   int     $organizationId
     * @param   string  $barcode
     * @return  Tote
     */
    public function barCodeExists ($organizationId, $barcode)
    {
        $query          = [
            'organizationIds'       => $organizationId,
            'barCodes'              => $barcode,
        ];

        $results                        = $this->toteRepo->where($query);

        if (sizeof($results) != 1)
            throw new NotFoundHttpException('Tote not found');

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

        $results                        = $this->toteRepo->where($query);

        if (sizeof($results) != 0)
            throw new NotFoundHttpException('Tote barCode already exists');
    }

}