<?php

use Illuminate\Database\Seeder;
use \LaravelDoctrine\ORM\Facades\EntityManager;
use App\Models\Integrations\ShoppingCartIntegration;
use App\Models\Integrations\IntegrationCredential;
use App\Models\Integrations\IntegrationWebHook;


class ShopifyShoppingCartIntegrationSeeder extends Seeder
{

    /**
     * @var \App\Repositories\Doctrine\Integrations\ShoppingCartIntegrationRepository
     */
    private $shoppingCartIntegrationRepo;

    public function run()
    {
        $this->shoppingCartIntegrationRepo     = EntityManager::getRepository('App\Models\Integrations\ShoppingCartIntegration');

        $this->shopify();
    }

    private function shopify ()
    {
        $shopifyIntegration        = new ShoppingCartIntegration();
        $shopifyIntegration->setName('Shopify');

        $shopifyApiKey                      = new IntegrationCredential();
        $shopifyApiKey->setName('apiKey');
        $shopifyApiKey->setIsRequired(true);
        $shopifyIntegration->addIntegrationCredential($shopifyApiKey);

        $shopifyPassword                    = new IntegrationCredential();
        $shopifyPassword->setName('password');
        $shopifyPassword->setIsRequired(true);
        $shopifyIntegration->addIntegrationCredential($shopifyPassword);

        $shopifyHostName                    = new IntegrationCredential();
        $shopifyHostName->setName('hostName');
        $shopifyHostName->setIsRequired(true);
        $shopifyIntegration->addIntegrationCredential($shopifyHostName);

        $shopifySharedSecret                = new IntegrationCredential();
        $shopifySharedSecret->setName('sharedSecret');
        $shopifySharedSecret->setIsRequired(true);
        $shopifyIntegration->addIntegrationCredential($shopifySharedSecret);


        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'app/uninstalled',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'carts/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'carts/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'checkouts/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'checkouts/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'checkouts/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'collections/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'collections/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'collections/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customer_groups/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customer_groups/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customer_groups/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/disable',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/enable',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'customers/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillment_events/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillment_events/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillments/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'fulfillments/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'order_transactions/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/cancelled',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/create',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/delete',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/fulfilled',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/paid',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/partially_fulfilled',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'orders/updated',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'products/create',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'products/delete',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'products/update',
                    'isActive'              => true
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'refunds/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'shop/update',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/create',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/delete',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/publish',
                    'isActive'              => false
                ]
            )
        );

        $shopifyIntegration->addIntegrationWebHook(
            new IntegrationWebHook(
                [
                    'topic'                 => 'themes/update',
                    'isActive'              => false
                ]
            )
        );

        $this->shoppingCartIntegrationRepo->saveAndCommit($shopifyIntegration);
    }
}
