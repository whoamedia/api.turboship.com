<?php

namespace App\Http\Controllers;


use App\Http\Requests\Variants\GetVariants;
use App\Jobs\Testing\ImportVariantExternalInventoryJob;
use App\Jobs\Testing\TransferPortableBinInventoryJob;
use App\Repositories\Doctrine\OMS\VariantRepository;
use EntityManager;
use Illuminate\Http\Request;


class TestingController extends BaseAuthController
{

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    public function __construct ()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
    }

    public function syncVariantExternalInventory (Request $request)
    {
        $getVariants                    = new GetVariants($request->input());
        $getVariants->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $getVariants->validate();
        $getVariants->clean();

        $query                          = $getVariants->jsonSerialize();

        $variantResults                 = $this->variantRepo->getSyncableVariants($query);
        $staffId                        = parent::getAuthUser()->getId();
        foreach ($variantResults AS $variant)
        {
            $job                        = (new ImportVariantExternalInventoryJob($variant['id'], $staffId, $request->input('portableBinId')))->onQueue('variantExternalInventorySync');
            $this->dispatch($job);
        }
    }

    public function transferVariantInventoryToBins (Request $request)
    {
        $staff                          = parent::getAuthStaff();
        $job                            = (new TransferPortableBinInventoryJob($staff->getId()))->onQueue('variantExternalInventorySync');
        $this->dispatch($job);
    }
}