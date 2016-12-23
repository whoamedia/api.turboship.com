<?php

use Illuminate\Database\Seeder;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Models\Integrations\ECommerceIntegration;
use App\Models\Integrations\IntegrationCredential;
use App\Models\Integrations\IntegrationWebHook;


class ShopifyECommerceIntegrationSeeder extends Seeder
{

    /**
     * @var \App\Repositories\Doctrine\Integrations\ECommerceIntegrationRepository
     */
    private $eCommerceIntegrationRepo;

    public function run()
    {
        $this->eCommerceIntegrationRepo     = EntityManager::getRepository('App\Models\Integrations\ECommerceIntegration');

        $this->shopify();
    }

    private function shopify ()
    {
        $shopifyECommerceIntegration        = new ECommerceIntegration();
        $shopifyECommerceIntegration->setName('Shopify');

        $shopifyApiKey                      = new IntegrationCredential();
        $shopifyApiKey->setName('apiKey');
        $shopifyApiKey->setIsRequired(true);
        $shopifyECommerceIntegration->addIntegrationCredential($shopifyApiKey);

        $shopifyPassword                    = new IntegrationCredential();
        $shopifyPassword->setName('password');
        $shopifyPassword->setIsRequired(true);
        $shopifyECommerceIntegration->addIntegrationCredential($shopifyPassword);

        $shopifyHostName                    = new IntegrationCredential();
        $shopifyHostName->setName('hostName');
        $shopifyHostName->setIsRequired(true);
        $shopifyECommerceIntegration->addIntegrationCredential($shopifyHostName);

        $shopifySharedSecret                = new IntegrationCredential();
        $shopifySharedSecret->setName('sharedSecret');
        $shopifySharedSecret->setIsRequired(true);
        $shopifyECommerceIntegration->addIntegrationCredential($shopifySharedSecret);


        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'app/uninstalled',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'carts/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'carts/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'checkouts/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'checkouts/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'checkouts/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'collections/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'collections/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'collections/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customer_groups/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customer_groups/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customer_groups/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/disable',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/enable',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillment_events/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillment_events/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillments/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillments/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'order_transactions/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/cancelled',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/create',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/delete',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/fulfilled',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/paid',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/partially_fulfilled',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/updated',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'products/create',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'products/delete',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'products/update',
                    'isActive'              => true
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'refunds/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'shop/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/publish',
                    'isActive'              => false
                ]
            )
        );

        $shopifyECommerceIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/update',
                    'isActive'              => false
                ]
            )
        );

        $this->eCommerceIntegrationRepo->saveAndCommit($shopifyECommerceIntegration);
    }
}
