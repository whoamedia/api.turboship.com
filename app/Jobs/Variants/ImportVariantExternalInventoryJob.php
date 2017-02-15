<?php

namespace App\Jobs\Orders\Variants;


use App\Jobs\Job;
use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\WMS\BinRepository;
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
    private $binId;

    /**
     * @var VariantRepository
     */
    private $variantRepo;

    /**
     * @var BinRepository
     */
    private $binRepo;

    public function __construct($variantId, $binId = null)
    {
        parent::__construct();

        $this->variantId                = $variantId;
        $this->binId                    = $binId;
    }


    public function handle()
    {
        $this->variantRepo              = EntityManager::getRepository('App\Models\OMS\Variant');
        $this->binRepo                  = EntityManager::getRepository('App\Models\WMS\Bin');

        $variant                        = $this->variantRepo->getOneById($this->variantId);

        $binQuery       = [
            'organizationIds'           => $variant->getClient()->getOrganization()->getId(),
        ];
        if (!is_null($this->binId))
            $binQuery['ids']            = $this->binId;

        $binResults                     = $this->binRepo->where($binQuery);
        $index                          = rand(0, sizeof($binResults) - 1);

        $variant->emptyInventory();

        for ($i = 0; $i < $variant->getExternalInventoryQuantity(); $i++)
        {
            $variantInventory           = new VariantInventory();
            $variantInventory->setVariant($variant);
            $variantInventory->setInventoryLocation($binResults[$index]);
            $variantInventory->setOrganization($variant->getClient()->getOrganization());
            $variant->addInventory($variantInventory);
        }

        $this->variantRepo->saveAndCommit($variant);
    }

}