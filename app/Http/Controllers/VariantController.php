<?php

namespace App\Http\Controllers;


use App\Http\Requests\Inventory\GetVariantInventory;
use App\Http\Requests\Variants\CreateVariantInventory;
use App\Http\Requests\Variants\GetVariants;
use App\Http\Requests\Variants\ShowVariant;
use App\Http\Requests\Variants\TransferVariantInventory;
use App\Jobs\Inventory\ReadyInventoryAddedJob;
use App\Models\OMS\Validation\VariantValidation;
use App\Models\OMS\Variant;
use App\Models\WMS\Bin;
use App\Models\WMS\Validation\InventoryLocationValidation;
use App\Models\WMS\Validation\PortableBinValidation;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\WMS\VariantInventoryRepository;
use App\Services\InventoryService;
use EntityManager;
use Illuminate\Http\Request;

class VariantController extends BaseAuthController
{

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var VariantInventoryRepository
     */
    private $variantInventoryRepo;

    public function __construct ()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->variantInventoryRepo     = EntityManager::getRepository('App\Models\WMS\VariantInventory');
    }


    public function index (Request $request)
    {
        $getVariants                    = new GetVariants($request->input());
        $getVariants->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getVariants->validate();
        $getVariants->clean();

        $query                          = $getVariants->jsonSerialize();
        $results                        = $this->variantRepo->where($query, false);
        return response($results);
    }

    public function getLexicon (Request $request)
    {
        $getVariants                    = new GetVariants($request->input());
        $getVariants->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getVariants->validate();
        $getVariants->clean();

        $query                          = $getVariants->jsonSerialize();
        $results                        = $this->variantRepo->getLexicon($query);
        return response($results);
    }

    public function show (Request $request)
    {
        $variant                        = $this->getVariantFromRoute($request->route('id'));
        return response($variant);
    }
    public function getInventory (Request $request)
    {
        $variant                        = $this->getVariantFromRoute($request->route('id'));

        $getVariantInventory            = new GetVariantInventory($request->input());
        $getVariantInventory->setVariantIds($variant->getId());
        $getVariantInventory->setGroupedReport(true);

        $query                          = $getVariantInventory->jsonSerialize();
        $results                        = $this->variantInventoryRepo->where($query);

        return response($results);
    }


    public function getInventoryLocations (Request $request)
    {
        $variant                        = $this->getVariantFromRoute($request->route('id'));

        $getVariantInventory            = new GetVariantInventory($request->input());
        $getVariantInventory->setVariantIds($variant->getId());
        $getVariantInventory->setInventoryLocationReport(true);

        $query                          = $getVariantInventory->jsonSerialize();
        $results                        = $this->variantInventoryRepo->where($query);

        return response($results);
    }

    public function createInventory (Request $request)
    {
        $createVariantInventory         = new CreateVariantInventory($request->input());
        $createVariantInventory->setId($request->route('id'));
        $createVariantInventory->validate();
        $createVariantInventory->clean();

        $variantValidation              = new VariantValidation();
        $portableBinValidation          = new PortableBinValidation();

        $staff                          = parent::getAuthStaff();
        $variant                        = $variantValidation->idExists($createVariantInventory->getId());
        $portableBin                    = $portableBinValidation->idExists($createVariantInventory->getPortableBinId());
        $quantity                       = $createVariantInventory->getQuantity();

        $inventoryService               = new InventoryService();
        $inventoryService->createVariantInventory($variant, $portableBin, $quantity, $staff);

        $this->variantRepo->saveAndCommit($variant);
        return response($variant);
    }

    public function transferInventory (Request $request)
    {
        $transferVariantInventory       = new TransferVariantInventory($request->input());
        $transferVariantInventory->setId($request->route('id'));
        $transferVariantInventory->validate();
        $transferVariantInventory->clean();

        $variantValidation              = new VariantValidation();
        $inventoryLocationValidation    = new InventoryLocationValidation();

        $staff                          = parent::getAuthStaff();
        $variant                        = $variantValidation->idExists($transferVariantInventory->getId());
        $fromInventoryLocation          = $inventoryLocationValidation->idExists($transferVariantInventory->getFromInventoryLocationId());
        $toInventoryLocation            = $inventoryLocationValidation->idExists($transferVariantInventory->getToInventoryLocationId());
        $quantity                       = $transferVariantInventory->getQuantity();

        $inventoryService               = new InventoryService();
        $inventoryService->transferVariantInventory($variant, $fromInventoryLocation, $toInventoryLocation, $quantity, $staff);

        $this->variantRepo->saveAndCommit($variant);

        if ($toInventoryLocation instanceof Bin)
        {
            $job                            = (new ReadyInventoryAddedJob($variant->getId()))->onQueue('shipmentInventoryReservation')->delay(config('turboship.variants.readyInventoryDelay'));
            $this->dispatch($job);
        }

        return response($variant);
    }


    /**
     * @param   int     $id
     * @return  Variant
     */
    private function getVariantFromRoute ($id)
    {
        $showVariant                    = new ShowVariant();
        $showVariant->setId($id);
        $showVariant->validate();
        $showVariant->clean();

        $variantValidation              = new VariantValidation();
        $variant                        = $variantValidation->idExists($showVariant->getId());
        return $variant;
    }
}