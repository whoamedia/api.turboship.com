<?php

namespace App\Jobs\Shopify;


use App\Jobs\Job;
use App\Models\Integrations\IntegratedShoppingCart;
use App\Models\Logs\ShopifyWebHookLog;
use App\Repositories\Doctrine\Integrations\IntegratedShoppingCartRepository;
use App\Repositories\Doctrine\Logs\ShopifyWebHookLogRepository;
use EntityManager;

class BaseShopifyJob extends Job
{

    /**
     * @var int
     */
    protected $integratedShoppingCartId;

    /**
     * @var string
     */
    protected $topic;

    /**
     * @var ShopifyWebHookLog
     */
    protected $shopifyWebHookLog;

    /**
     * @var IntegratedShoppingCartRepository
     */
    protected $integratedShoppingCartRepo;

    /**
     * @var IntegratedShoppingCart
     */
    protected $integratedShoppingCart;

    /**
     * @var ShopifyWebHookLogRepository
     */
    protected $shopifyWebHookLogRepo;


    /**
     * BaseShopifyJob constructor.
     * @param   int                         $integratedShoppingCartId
     * @param   string                      $topic
     * @param   ShopifyWebHookLog|null      $shopifyWebHookLog
     */
    public function __construct($integratedShoppingCartId, $topic, $shopifyWebHookLog = null)
    {
        parent::__construct();
        $this->integratedShoppingCartId = $integratedShoppingCartId;
        $this->topic                    = $topic;
        $this->shopifyWebHookLog        = $shopifyWebHookLog;
    }


    protected function initialize ($externalId)
    {
        $this->integratedShoppingCartRepo = EntityManager::getRepository('App\Models\Integrations\IntegratedShoppingCart');
        $this->shopifyWebHookLogRepo    = EntityManager::getRepository('App\Models\Logs\ShopifyWebHookLog');
        $this->integratedShoppingCart   = $this->integratedShoppingCartRepo->getOneById($this->integratedShoppingCartId);

        $this->createShopifyWebHookLog($externalId);
    }

    private function createShopifyWebHookLog ($externalId)
    {
        if (is_null($this->shopifyWebHookLog))
        {
            $this->shopifyWebHookLog        = new ShopifyWebHookLog();
            $this->shopifyWebHookLog->setEntityCreated(false);
        }

        if (!is_null($this->shopifyWebHookLog->isVerified()))
            $this->shopifyWebHookLog->setVerified(true);

        $this->shopifyWebHookLog->setTopic($this->topic);
        $this->shopifyWebHookLog->setExternalId($externalId);
        $this->shopifyWebHookLog->setIntegratedShoppingCart($this->integratedShoppingCart);

        $this->shopifyWebHookLogRepo->saveAndCommit($this->shopifyWebHookLog);
    }
}