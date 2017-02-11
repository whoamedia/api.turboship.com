<?php

namespace App\Models\Hardware\Validation;


use App\Models\Hardware\Printer;
use App\Repositories\Doctrine\Hardware\PrinterRepository;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrinterValidation
{

    /**
     * @var PrinterRepository
     */
    private $printerRepo;


    public function __construct()
    {
        $this->printerRepo                  = EntityManager::getRepository('App\Models\Hardware\Printer');
    }

    /**
     * @param   int     $id
     * @return  Printer
     * @throws  NotFoundHttpException
     */
    public function idExists($id)
    {
        $printer                            = $this->printerRepo->getOneById($id);

        if (is_null($printer))
            throw new NotFoundHttpException('Printer not found');

        return $printer;
    }

}