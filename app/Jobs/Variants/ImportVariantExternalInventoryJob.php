<?php

namespace App\Jobs\Variants;


use App\Jobs\Job;
use App\Models\WMS\Bin;
use App\Models\WMS\VariantInventory;
use App\Repositories\Doctrine\OMS\VariantRepository;
use App\Repositories\Doctrine\WMS\BinRepository;
use App\Repositories\Doctrine\WMS\VariantInventoryRepository;
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
     * @var VariantInventoryRepository
     */
    private $variantInventoryRepo;

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
        $this->variantInventoryRepo     = EntityManager::getRepository('App\Models\WMS\VariantInventory');
        $this->binRepo                  = EntityManager::getRepository('App\Models\WMS\Bin');

        $variant                        = $this->variantRepo->getOneById($this->variantId);

        $binQuery       = [
            'organizationIds'           => $variant->getClient()->getOrganization()->getId(),
        ];
        if (!is_null($this->binId))
            $binQuery['ids']            = $this->binId;

        $binResults                     = $this->binRepo->where($binQuery);
        $index                          = rand(0, sizeof($binResults) - 1);
        $bin                            = $binResults[$index];


        /**
         * Need to delete all VariantInventory that has this Variant id
         */

        for ($i = 0; $i < $variant->getExternalInventoryQuantity(); $i++)
        {
            $variantInventory           = new VariantInventory();
            $variantInventory->setVariant($variant);
            $variantInventory->setOrganization($variant->getClient()->getOrganization());
            $bin->addInventory($variantInventory);
        }

        $this->binRepo->saveAndCommit($bin);
    }

}