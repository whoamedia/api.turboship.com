<?php

namespace App\Jobs\Testing;


use App\Jobs\Job;
use App\Repositories\Doctrine\CMS\StaffRepository;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\WMS\PortableBinRepository;
use App\Services\InventoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ImportVariantExternalInventoryJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var int
     */
    private $variantId;

    /**
     * @var int
     */
    private $staffId;

    /**
     * @var int|null
     */
    private $portableBinId;

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var PortableBinRepository
     */
    private $portableBinRepo;

    /**
     * @var StaffRepository
     */
    private $staffRepo;


    public function __construct($variantId, $staffId, $portableBinId = null)
    {
        parent::__construct();

        $this->variantId                = $variantId;
        $this->staffId                  = $staffId;
        $this->portableBinId            = $portableBinId;
    }


    public function handle()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->portableBinRepo          = EntityManager::getRepository('App\Models\WMS\PortableBin');
        $this->staffRepo                = EntityManager::getRepository('App\Models\CMS\Staff');

        $variant                        = $this->variantRepo->getOneById($this->variantId);
        $staff                          = $this->staffRepo->getOneById($this->staffId);

        $binQuery       = [
            'organizationIds'           => $variant->getClient()->getOrganization()->getId(),
        ];
        if (!is_null($this->portableBinId))
            $binQuery['ids']            = $this->portableBinId;

        $portableBinResults             = $this->portableBinRepo->where($binQuery);
        $inventoryService               = new InventoryService();

        $index                          = rand(0, sizeof($portableBinResults) - 1);
        $portableBin                    = $portableBinResults[$index];

        $inventoryService->createVariantInventory($variant, $portableBin, $variant->getExternalInventoryQuantity(), $staff);

        $this->variantRepo->saveAndCommit($variant);
    }

}