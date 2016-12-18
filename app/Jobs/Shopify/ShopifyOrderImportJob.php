<?php

namespace App\Jobs\Shopify;

use App\Repositories\Doctrine\CMS\ClientRepository;
use App\Repositories\Shopify\ShopifyOrderRepository;
use EntityManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShopifyOrderImportJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var int
     */
    private $clientId;

    /**
     * @var ClientRepository
     */
    private $clientRepo;


    /**
     * ShopifyOrderImportJob constructor.
     * @param   int     $clientId
     */
    public function __construct($clientId)
    {
        $this->clientId                 = $clientId;
        $this->clientRepo               = EntityManager::getRepository('App\Models\CMS\Client');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client                         = $this->clientRepo->getOneById($this->clientId);
        $shopifyOrderRepository         = new ShopifyOrderRepository($client);
        $shopifyOrderRepository->downloadOrders();
    }
}
