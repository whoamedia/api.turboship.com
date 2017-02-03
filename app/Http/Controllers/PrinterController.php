<?php

namespace App\Http\Controllers;


use App\Http\Requests\Printers\CreatePrinter;
use App\Http\Requests\Printers\GetPrinters;
use App\Http\Requests\Printers\ShowPrinter;
use App\Http\Requests\Printers\UpdatePrinter;
use App\Models\WMS\Printer;
use App\Models\WMS\Validation\PrinterTypeValidation;
use App\Repositories\Doctrine\WMS\PrinterRepository;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrinterController extends BaseAuthController
{

    /**
     * @var PrinterRepository
     */
    private $printerRepo;


    public function __construct()
    {
        $this->printerRepo              = EntityManager::getRepository('App\Models\WMS\Printer');
    }


    public function index (Request $request)
    {
        $getPrinters                    = new GetPrinters($request->input());
        $getPrinters->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getPrinters->validate();
        $getPrinters->clean();

        $query                          = $getPrinters->jsonSerialize();
        $results                        = $this->printerRepo->where($query, false);

        return response($results);
    }

    public function store (Request $request)
    {
        $createPrinter                  = new CreatePrinter($request->input());
        $createPrinter->validate();
        $createPrinter->clean();

        $printer                        = new Printer($createPrinter->jsonSerialize());
        $printer->setOrganization(parent::getAuthUserOrganization());

        $printerTypeValidation          = new PrinterTypeValidation();
        $printerType                    = $printerTypeValidation->idExists($createPrinter->getPrinterTypeId());
        $printer->setPrinterType($printerType);

        $this->printerRepo->saveAndCommit($printer);

        return response($printer, 201);
    }

    public function show (Request $request)
    {
        $printer                        = $this->getPrinterFromRoute($request->route('id'));
        return response($printer);
    }

    public function update (Request $request)
    {
        $printer                        = $this->getPrinterFromRoute($request->route('id'));
        $updatePrinter                  = new UpdatePrinter($request->input());
        $updatePrinter->setId($printer->getId());
        $updatePrinter->validate();
        $updatePrinter->clean();

        if (!is_null($updatePrinter->getName()))
            $printer->setName($updatePrinter->getName());

        if (!is_null($updatePrinter->getDescription()))
            $printer->setDescription($updatePrinter->getDescription());

        if (!is_null($updatePrinter->getIpAddress()))
            $printer->setIpAddress($updatePrinter->getIpAddress());

        if (!is_null($updatePrinter->getPrinterTypeId()))
        {
            $printerTypeValidation      = new PrinterTypeValidation();
            $printerType                = $printerTypeValidation->idExists($updatePrinter->getPrinterTypeId());
            $printer->setPrinterType($printerType);
        }


        $this->printerRepo->saveAndCommit($printer);
        return response($printer);
    }

    private function getPrinterFromRoute ($id)
    {
        $showPrinter                    = new ShowPrinter();
        $showPrinter->setId($id);
        $showPrinter->validate();
        $showPrinter->clean();

        $printer                          = $this->printerRepo->getOneById($showPrinter->getId());
        if (is_null($printer))
            throw new NotFoundHttpException('Printer not found');

        if ($printer->getOrganization()->getId() != parent::getAuthUserOrganization()->getId())
            throw new NotFoundHttpException('Printer not found');

        return $printer;
    }
}