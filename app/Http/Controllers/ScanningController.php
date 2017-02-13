<?php

namespace App\Http\Controllers;


use App\Http\Requests\Inventory\CreateVariantInventory;
use App\Http\Requests\Scanning\ShowScannedBin;
use App\Http\Requests\Scanning\ShowScannedTote;
use App\Http\Requests\Scanning\ShowScannedVariantInventory;
use App\Models\OMS\Validation\VariantValidation;
use App\Models\WMS\Validation\BinValidation;
use App\Models\WMS\Validation\ToteValidation;
use App\Models\WMS\Validation\VariantInventoryValidation;
use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\WMS\VariantInventoryRepository;
use Illuminate\Http\Request;
use EntityManager;

class ScanningController extends BaseAuthController
{

    /**
     * @var VariantInventoryRepository
     */
    private $variantInventoryRepo;

    /**
     * @var VariantInventoryValidation
     */
    private $variantInventoryValidation;

    public function __construct ()
    {
        $this->variantInventoryRepo     = EntityManager::getRepository('App\Models\WMS\VariantInventory');
        $this->variantInventoryValidation= new VariantInventoryValidation($this->variantInventoryRepo);
    }

    public function showVariantInventory (Request $request)
    {
        $barCode                        = $this->getBarcodeFromRoute($request->route('barCode'));
        $variantInventory               = $this->variantInventoryValidation->barCodeExists(parent::getAuthUserOrganization()->getId(), $barCode);

        return response($variantInventory);
    }

    public function createVariantInventory (Request $request)
    {
        $createVariantInventory         = new CreateVariantInventory($request->input());
        $createVariantInventory->setOrganizationId(parent::getAuthUserOrganization()->getId());
        $createVariantInventory->validate();
        $createVariantInventory->clean();

        $variantValidation              = new VariantValidation();
        $variant                        = $variantValidation->idExists($createVariantInventory->getVariantId());

        $binValidation                  = new BinValidation();
        $bin                            = $binValidation->idExists($createVariantInventory->getBinId());

        $this->variantInventoryValidation->barCodeDoesNotExist($createVariantInventory->getOrganizationId(), $createVariantInventory->getBarCode());

        $variantInventory               = new VariantInventory();
        $variantInventory->setVariant($variant);
        $variantInventory->setOrganization(parent::getAuthUserOrganization());
        $variantInventory->setInventoryLocation($bin);
        $variantInventory->setBarCode($createVariantInventory->getBarCode());

        $this->variantInventoryRepo->saveAndCommit($variantInventory);
        return response($variantInventory, 201);
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

    private function getBarcodeFromRoute ($barCode)
    {
        $showVariantInventory           = new ShowScannedVariantInventory();
        $showVariantInventory->setBarCode($barCode);
        $showVariantInventory->validate();
        $showVariantInventory->clean();

        return $showVariantInventory->getBarCode();
    }
}