<?php

namespace App\Http\Controllers;


use App\Http\Requests\Scanning\ShowScannedBin;
use App\Http\Requests\Scanning\ShowScannedTote;
use App\Http\Requests\Scanning\ShowScannedVariant;
use App\Models\OMS\Validation\VariantValidation;
use App\Models\WMS\Validation\BinValidation;
use App\Models\WMS\Validation\ToteValidation;
use Illuminate\Http\Request;

class ScanningController extends BaseAuthController
{


    public function __construct ()
    {

    }

    public function showVariant (Request $request)
    {
        $showScannedVariant             = new ShowScannedVariant();
        $showScannedVariant->setBarCode($request->route('barCode'));
        $showScannedVariant->validate();
        $showScannedVariant->clean();

        $variantValidation              = new VariantValidation();
        $variant                        = $variantValidation->barCodeExists(parent::getAuthUserOrganization()->getId(), $showScannedVariant->getBarCode());

        return response($variant);
    }

    public function showBin (Request $request)
    {
        $showScannedBin                 = new ShowScannedBin();
        $showScannedBin->setBarCode($request->route('barCode'));
        $showScannedBin->validate();
        $showScannedBin->clean();

        $barCode                        = $showScannedBin->getBarCode();
        $binValidation                  = new BinValidation();

        $bin                            = $binValidation->barCodeExists(parent::getAuthUserOrganization()->getId(), $barCode);
        return response($bin);
    }

    public function showTote (Request $request)
    {
        $showScannedTote                = new ShowScannedTote();
        $showScannedTote->setBarCode($request->route('barCode'));
        $showScannedTote->validate();
        $showScannedTote->clean();

        $barCode                        = $showScannedTote->getBarCode();
        $toteValidation                 = new ToteValidation();
        $tote                           = $toteValidation->barCodeExists(parent::getAuthUserOrganization()->getId(), $barCode);
        return response($tote);
    }

}