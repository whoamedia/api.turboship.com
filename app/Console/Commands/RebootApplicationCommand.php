<?php

namespace App\Console\Commands;


use App\Repositories\Doctrine\Integrations\IntegratedWebHookRepository;
use App\Repositories\Shopify\ShopifyWebHookRepository;
use Illuminate\Console\Command;
use EntityManager;

class RebootApplicationCommand extends Command
{

    protected $signature = 'turboship:reboot';

    protected $description = 'Reverses migrations. Migrates. Seeds.';


    /**
     * @var IntegratedWebHookRepository
     */
    protected $integratedWebHookRepo;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //  $this->deleteExternalWebHooks();

        try {
            $this->call('migrate:refresh', [
                '--seed' => 1
            ]);
        }
        catch (\Exception $ex)
        {
            $this->info('migrate:refresh --seed threw the following Exception');
            $this->error($ex->getMessage());
        }

        $this->call('turboship:client:create', [
            'clientName'    =>  'james@turboship.com',
            'clientId'      =>  'seloVYGtW6yFM1iz',
            'clientSecret'  =>  'b175ZuxK0041VTYU1fLJoxVT72CrqG1v'
        ]);
    }


    private function deleteExternalWebHooks ()
    {
        try
        {
            $this->integratedWebHookRepo        = EntityManager::getRepository('App\Models\Integrations\IntegratedWebHook');
            $results                            = $this->integratedWebHookRepo->where([]);

            foreach ($results AS $integratedWebHook)
            {
                $shopifyWebHookRepository       = new ShopifyWebHookRepository($integratedWebHook->getIntegratedService());
                $this->info('Deleting integratedWebHook id ' . $integratedWebHook->getId());
                $shopifyWebHookRepository->deleteWebHook($integratedWebHook->getExternalId());
            }
        }
        catch (\Exception $ex)
        {
            // do nothing
        }
    }
}
