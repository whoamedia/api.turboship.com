<?php

namespace App\Jobs\Variants;


use App\Jobs\Job;
use App\Models\WMS\VariantInventory;
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

    public function __construct($variantId, $portableBinId = null)
    {
        parent::__construct();

        $this->variantId                = $variantId;
        $this->portableBinId            = $portableBinId;
    }


    public function handle()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->portableBinRepo          = EntityManager::getRepository('App\Models\WMS\PortableBin');

        $variant                        = $this->variantRepo->getOneById($this->variantId);

        $binQuery       = [
            'organizationIds'           => $variant->getClient()->getOrganization()->getId(),
        ];
        if (!is_null($this->portableBinId))
            $binQuery['ids']            = $this->portableBinId;

        $portableBinResults             = $this->portableBinRepo->where($binQuery);
        $inventoryService               = new InventoryService();

        $index                          = rand(0, sizeof($portableBinResults) - 1);
        $portableBin                    = $portableBinResults[$index];
        $inventoryService->createVariantInventory($variant, $portableBin, $variant->getExternalInventoryQuantity());

        $this->variantRepo->saveAndCommit($variant);
    }

}