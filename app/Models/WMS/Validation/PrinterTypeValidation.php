<?php

namespace App\Models\WMS\Validation;

use App\Models\WMS\PrinterType;
use App\Repositories\Doctrine\WMS\PrinterTypeRepository;
use App\Utilities\PrinterTypeUtility;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrinterTypeValidation
{

    /**
     * @var PrinterTypeRepository
     */
    private $printerTypeRepo;


    public function __construct()
    {
        $this->printerTypeRepo              = EntityManager::getRepository('App\Models\WMS\PrinterType');
    }

    /**
     * @param   int     $id
     * @return  PrinterType
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $printerType                        = $this->printerTypeRepo->getOneById($id);

        if (is_null($printerType))
            throw new NotFoundHttpException('PrinterType not found');

        return $printerType;
    }

    /**
     * @return PrinterType
     */
    public function getCUPSServer ()
    {
        return $this->idExists(PrinterTypeUtility::CUPS_SERVER);
    }

}