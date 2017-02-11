<?php

namespace App\Http\Controllers;


use App\Http\Requests\Printers\CreateCUPSPrinter;
use App\Http\Requests\Printers\GetPrinters;
use App\Http\Requests\Printers\PrintPostageLabel;
use App\Http\Requests\Printers\PrintShipmentLabel;
use App\Http\Requests\Printers\ShowPrinter;
use App\Http\Requests\Printers\UpdateCUPSPrinter;
use App\Models\Hardware\CUPSPrinter;
use App\Models\Hardware\PrinterType;
use App\Models\Hardware\Validation\PrinterTypeValidation;
use App\Models\Shipments\Validation\PostageValidation;
use App\Models\Shipments\Validation\ShipmentValidation;
use App\Repositories\Doctrine\Hardware\PrinterRepository;
use App\Services\PrinterService;
use App\Utilities\PrinterTypeUtility;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrinterController extends BaseAuthController
{

    /**
     * @var PrinterRepository
     */
    private $printerRepo;

    /**
     * @var PrinterTypeValidation
     */
    private $printerTypeValidation;


    public function __construct()
    {
        $this->printerRepo              = EntityManager::getRepository('App\Models\Hardware\Printer');
        $this->printerTypeValidation    = new PrinterTypeValidation();
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
        $printerType                    = $this->validatePrinterType($request->input('printerTypeId'));

        if ($printerType->getId() == PrinterTypeUtility::CUPS_SERVER)
        {
            $createCupsPrinter          = new CreateCUPSPrinter($request->input());
            $createCupsPrinter->validate();
            $createCupsPrinter->clean();

            $cupsPrinter                = new CUPSPrinter($createCupsPrinter->jsonSerialize());
            $cupsPrinter->setOrganization(parent::getAuthUserOrganization());

            $this->printerRepo->saveAndCommit($cupsPrinter);
            return response($cupsPrinter, 201);
        }
        else
            throw new BadRequestHttpException('printerTypeId not supported');

    }

    public function show (Request $request)
    {
        $printer                        = $this->getPrinterFromRoute($request->route('id'));
        return response($printer);
    }

    public function update (Request $request)
    {
        $printer                        = $this->getPrinterFromRoute($request->route('id'));

        if ($printer->getObject() == 'CUPSPrinter')
        {
            $updateCUPSPrinter              = new UpdateCUPSPrinter($request->input());
            $updateCUPSPrinter->setId($printer->getId());
            $updateCUPSPrinter->validate();
            $updateCUPSPrinter->clean();

            if (!is_null($updateCUPSPrinter->getName()))
                $printer->setName($updateCUPSPrinter->getName());

            if (!is_null($updateCUPSPrinter->getDescription()))
                $printer->setDescription($updateCUPSPrinter->getDescription());

            if (!is_null($updateCUPSPrinter->getAddress()))
                $printer->setAddress($updateCUPSPrinter->getAddress());

            if (!is_null($updateCUPSPrinter->getPort()))
                $printer->setPort($updateCUPSPrinter->getPort());

            if (!is_null($updateCUPSPrinter->getFormat()))
                $printer->setFormat($updateCUPSPrinter->getFormat());
        }
        else
            throw new BadRequestHttpException('printerTypeId not supported');


        $this->printerRepo->saveAndCommit($printer);
        return response($printer);
    }

    public function printShipmentLabel (Request $request)
    {
        $printShipmentLabel             = new PrintShipmentLabel();
        $printShipmentLabel->setId($request->route('id'));
        $printShipmentLabel->setShipmentId($request->route('shipmentId'));
        $printShipmentLabel->validate();
        $printShipmentLabel->clean();

        $printer                        = $this->getPrinterFromRoute($printShipmentLabel->getId());
        $shipmentValidation             = new ShipmentValidation();
        $shipment                       = $shipmentValidation->idExists($printShipmentLabel->getShipmentId());

        if (is_null($shipment->getPostage()))
            throw new BadRequestHttpException('Shipment does not have postage');

        $printerService                 = new PrinterService();
        $printerService->printLabel($shipment->getPostage(), $printer);

        return response('', 200);
    }

    public function printPostageLabel (Request $request)
    {
        $printPostageLabel             = new PrintPostageLabel();
        $printPostageLabel->setId($request->route('id'));
        $printPostageLabel->setPostageId($request->route('postageId'));
        $printPostageLabel->validate();
        $printPostageLabel->clean();

        $printer                        = $this->getPrinterFromRoute($printPostageLabel->getId());
        $postageValidation              = new PostageValidation();
        $postage                        = $postageValidation->idExists($printPostageLabel->getPostageId());

        $printerService                 = new PrinterService();
        $printerService->printLabel($postage, $printer);

        return response('', 200);
    }



    /**
     * @param   int     $printerTypeId
     * @return  PrinterType
     */
    private function validatePrinterType ($printerTypeId)
    {
        if (is_null($printerTypeId))
            throw new BadRequestHttpException('printerType id is required');

        $printerType                    = $this->printerTypeValidation->idExists($printerTypeId);
        return $printerType;
    }

    /**
     * @param   int $id
     * @return  CUPSPrinter
     */
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