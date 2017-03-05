<?php

namespace App\Services\IPP;


use App\Models\Hardware\CUPSPrinter;
use App\Models\OMS\Variant;
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

    /**
     * @param Variant       $variant
     * @param CUPSPrinter   $printer
     * @param int           $copies
     */
    public function printVariantBarCode (Variant $variant, $printer, $copies = 1)
    {
        $data = '^XA
                ^CF0,10
                ^LH0,10
                ^FO40,20^AD^FD{!YOUR PRODUCT NAME HERE!}^FS
                ^FO150,50^BY1,2.0^BCN,60,N,N,N,A^FD{!BARCODE DATA HERE!}^FS
                ^FO40,130^AD^FD{!VARIANT NAME!}   {!BARCODE HERE!}^FS
                ^PQ{!COPIES!}
                ^XZ';

        $data   = str_replace('YOUR PRODUCT NAME HERE', $variant->getProduct()->getName(), $data);
        $data   = str_replace('VARIANT NAME', $variant->getTitle(), $data);
        $data   = str_replace('BARCODE HERE', $variant->getBarCode(), $data);
        $data   = str_replace('COPIES', $copies, $data);

        $print                          = new PrintIPP();
        $print->setPort($printer->getPort());
        $print->setHost($printer->getAddress());
        $print->setPrinterURI('/printers/' . $printer->getName());
        $print->setRawText();
        $print->setData($data);
        $print->printJob();
    }

}