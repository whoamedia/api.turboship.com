<?php

use Illuminate\Database\Seeder;
use App\Utilities\IntegrationUtility;

class IntegrationWebHookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('IntegrationWebHook')->insert(
            $this->getShopify()
        );
    }


    public function getShopify ()
    {
        return [
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'app/uninstalled',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'carts/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'carts/update',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'checkouts/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'checkouts/delete',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'checkouts/update',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'collections/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'collections/delete',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'collections/update',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customer_groups/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customer_groups/delete',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customer_groups/update',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customers/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customers/delete',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customers/disable',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customers/enable',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'customers/update',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'fulfillment_events/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'fulfillment_events/delete',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'fulfillments/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'fulfillments/update',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'order_transactions/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'orders/cancelled',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'orders/create',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'orders/delete',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'orders/fulfilled',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'orders/paid',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'orders/partially_fulfilled',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'orders/updated',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'products/create',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'products/delete',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'products/update',
                'isActive'              => true,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'refunds/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'shop/update',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'themes/create',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'themes/delete',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'themes/publish',
                'isActive'              => false,
            ],
            [
                'integrationId'         => IntegrationUtility::SHOPIFY_ID,
                'topic'                 => 'themes/update',
                'isActive'              => false,
            ],
        ];
    }
}
