<?php

namespace App\Jobs\Testing;


use App\Http\Requests\Inventory\GetVariantInventory;
use App\Jobs\Job;
use App\Repositories\Doctrine\CMS\StaffRepository;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\WMS\BinRepository;
use App\Repositories\Doctrine\WMS\PortableBinRepository;
use App\Repositories\Doctrine\WMS\VariantInventoryRepository;
use App\Services\InventoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class TransferPortableBinInventoryJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var int
     */
    private $staffId;

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var BinRepository
     */
    private $binRepo;

    /**
     * @var PortableBinRepository
     */
    private $portableBinRepo;

    /**
     * @var StaffRepository
     */
    private $staffRepo;

    /**
     * @var VariantInventoryRepository
     */
    private $variantInventoryRepo;


    public function __construct($staffId)
    {
        parent::__construct();

        $this->staffId                  = $staffId;
    }


    public function handle()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->binRepo                  = EntityManager::getRepository('App\Models\WMS\Bin');
        $this->portableBinRepo          = EntityManager::getRepository('App\Models\WMS\PortableBin');
        $this->staffRepo                = EntityManager::getRepository('App\Models\CMS\Staff');
        $this->variantInventoryRepo     = EntityManager::getRepository('App\Models\WMS\VariantInventory');

        $inventoryService               = new InventoryService();
        $staff                          = $this->staffRepo->getOneById($this->staffId);

        $binQuery       = [
            'organizationIds'           => $staff->getOrganization()->getId(),
        ];

        $portableBinResults             = $this->portableBinRepo->where($binQuery);
        $binResults                     = $this->binRepo->where($binQuery);


        foreach ($portableBinResults AS $portableBin)
        {
            $getVariantInventory        = new GetVariantInventory();
            $getVariantInventory->setInventoryLocationIds($portableBin->getId());
            $getVariantInventory->setGroupedReport(true);

            $query                      = $getVariantInventory->jsonSerialize();
            $variantInventoryResults    = $this->variantInventoryRepo->where($query);

            foreach ($variantInventoryResults AS $variantInventory)
            {
                $variantId              = $variantInventory['variant']['id'];
                $variant                = $this->variantRepo->getOneById($variantId);
                $total                  = $variantInventory['total'];
                $index                  = rand(0, sizeof($binResults) - 1);
                $bin                    = $binResults[$index];

                $inventoryService->transferVariantInventory($variant, $portableBin, $bin, $total, $staff);
                $this->variantRepo->save($variant);
            }
            $this->variantRepo->commit();
        }
    }
}