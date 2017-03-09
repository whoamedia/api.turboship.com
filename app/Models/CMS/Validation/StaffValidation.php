<?php

namespace App\Models\CMS\Validation;


use App\Models\CMS\Staff;
use App\Repositories\Doctrine\CMS\StaffRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaffValidation
{

    /**
     * @var StaffRepository
     */
    protected $staffRepo;


    /**
     * StaffValidation constructor.
     * @param StaffRepository|null $staffRepo
     */
    public function __construct($staffRepo = null)
    {
        if (is_null($staffRepo))
            $staffRepo                  = EntityManager::getRepository('App\Models\CMS\Staff');

        $this->staffRepo                = $staffRepo;
    }

    /**
     * @param   int     $id
     * @return  Staff
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $staff                          = $this->staffRepo->getOneById($id);

        if (is_null($staff))
            throw new NotFoundHttpException('Staff not found');

        return $staff;
    }

    /**
     * @param   int     $organizationId
     * @param   string  $barcode
     * @return  Staff
     */
    public function barCodeExists ($organizationId, $barcode)
    {
        $query          = [
            'organizationIds'       => $organizationId,
            'barCodes'              => $barcode,
        ];

        $results                        = $this->staffRepo->where($query);

        if (sizeof($results) != 1)
            throw new NotFoundHttpException('Staff not found');

        return $results[0];
    }

}