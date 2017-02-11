<?php

namespace App\Services\IPP;


use App\Models\Hardware\CUPSPrinter;
use App\Models\Shipments\Postage;
use App\Services\IPP\Support\PrintIPP;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IPPService
{

    public function __construct()
    {

    }

    /**
     * @param Postage       $postage
     * @param CUPSPrinter   $printer
     */
    public function printLabel (Postage $postage, $printer)
    {
        if ($printer->getFormat() != 'ZPL')
            throw new BadRequestHttpException('Format ' . $printer->getFormat() . ' is not supported');

        $labelContents                  = file_get_contents($postage->getZplPath());
        $print                          = new PrintIPP();
        $print->setPort($printer->getPort());
        $print->setHost($printer->getAddress());
        $print->setPrinterURI('/printers/' . $printer->getName());
        $print->setRawText();
        $print->setData($labelContents);

        $print->printJob();
    }

}