<?php

namespace App\Http\Controllers;


use App\Http\Requests\Scanning\ShowScannedBin;
use App\Http\Requests\Scanning\ShowScannedCart;
use App\Http\Requests\Scanning\ShowScannedPortableBin;
use App\Http\Requests\Scanning\ShowScannedTote;
use App\Http\Requests\Scanning\ShowScannedStaff;
use App\Http\Requests\Scanning\ShowScannedVariant;
use App\Models\CMS\Validation\StaffValidation;
use App\Models\OMS\Validation\VariantValidation;
use App\Models\WMS\Validation\BinValidation;
use App\Models\WMS\Validation\CartValidation;
use App\Models\WMS\Validation\PortableBinValidation;
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

    public function showCart (Request $request)
    {
        $showScannedCart                = new ShowScannedCart();
        $showScannedCart->setBarCode($request->route('barCode'));
        $showScannedCart->validate();
        $showScannedCart->clean();

        $barCode                        = $showScannedCart->getBarCode();
        $cartValidation                 = new CartValidation();
        $cart                           = $cartValidation->barCodeExists(parent::getAuthUserOrganization()->getId(), $barCode);
        return response($cart);
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

    public function showStaff (Request $request)
    {
        $showScannedUser                = new ShowScannedStaff();
        $showScannedUser->setBarCode($request->route('barCode'));
        $showScannedUser->validate();
        $showScannedUser->clean();

        $barCode                        = $showScannedUser->getBarCode();
        $staffValidation                = new StaffValidation();
        $staff                          = $staffValidation->barCodeExists(parent::getAuthUserOrganization()->getId(), $barCode);
        return response($staff);
    }

    public function showPortableBin (Request $request)
    {
        $showScannedPortableBin         = new ShowScannedPortableBin();
        $showScannedPortableBin->setBarCode($request->route('barCode'));
        $showScannedPortableBin->validate();
        $showScannedPortableBin->clean();

        $barCode                        = $showScannedPortableBin->getBarCode();
        $portableBinValidation          = new PortableBinValidation();
        $portableBin                    = $portableBinValidation->barCodeExists(parent::getAuthUserOrganization()->getId(), $barCode);

        return response($portableBin);
    }

}