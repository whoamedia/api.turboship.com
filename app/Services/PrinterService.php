<?php

namespace App\Services;


use App\Models\Hardware\CUPSPrinter;
use App\Models\Hardware\Printer;
use App\Models\OMS\Variant;
use App\Models\Shipments\Postage;
use App\Services\IPP\IPPService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PrinterService
{

    /**
     * @var IPPService
     */
    private $ippService;

    public function __construct()
    {
        $this->ippService               = new IPPService();
    }

    /**
     * @param Postage       $postage
     * @param Printer       $printer
     */
    public function printLabel (Postage $postage, $printer)
    {

        if ($printer instanceof CUPSPrinter)
        {
            $this->ippService->printLabel($postage, $printer);
        }
        else
            throw new BadRequestHttpException('Printer is not supported');
    }

    /**
     * @param Variant       $variant
     * @param Printer       $printer
     */
    public function printVariantBarCode (Variant $variant, $printer)
    {

        if ($printer instanceof CUPSPrinter)
        {
            $this->ippService->printVariantBarCode($variant, $printer);
        }
        else
            throw new BadRequestHttpException('Printer is not supported');
    }

}