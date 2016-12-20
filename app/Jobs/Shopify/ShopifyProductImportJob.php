<?php

namespace App\Jobs\Shopify;


use App\Repositories\Doctrine\Integrations\ClientIntegrationRepository;
use App\Services\Shopify\ShopifyProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ShopifyProductImportJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $clientIntegrationId;

    /**
     * @var ClientIntegrationRepository
     */
    private $clientIntegrationRepo;


    /**
     * ShopifyOrderImportJob constructor.
     * @param   int     $clientIntegrationId
     */
    public function __construct($clientIntegrationId)
    {
        $this->clientIntegrationId      = $clientIntegrationId;
        $this->clientIntegrationRepo    = EntityManager::getRepository('App\Models\Integrations\ClientIntegration');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clientIntegration              = $this->clientIntegrationRepo->getOneById($this->clientIntegrationId);
        $shopifyProductService          = new ShopifyProductService($clientIntegration);
        $shopifyProductService->download();
    }

}
