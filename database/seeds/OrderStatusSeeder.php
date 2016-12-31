<?php

use Illuminate\Database\Seeder;
use \App\Utilities\OrderStatusUtility;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('OrderStatus')->insert(
            $this->getSources()
        );
    }

    private function getSources ()
    {
        return [

            /**
             * LifeCycle
             */
            [
                'id'    => OrderStatusUtility::CREATED_ID,
                'name'  => 'Created',
                'isError' => false
            ],
            [
                'id'    => OrderStatusUtility::CANCELLED,
                'name'  => 'Cancelled',
                'isError' => false
            ],




            /**
             * Address Error Statuses
             */
            [
                'id'    => OrderStatusUtility::INVALID_CITY_ID,
                'name'  => 'Invalid City',
                'isError' => true
            ],
            [
                'id'    => OrderStatusUtility::INVALID_STATE_ID,
                'name'  => 'Invalid State',
                'isError' => true
            ],
            [
                'id'    => OrderStatusUtility::INVALID_ADDRESS_ID,
                'name'  => 'Invalid Address',
                'isError' => true
            ],
            [
                'id'    => OrderStatusUtility::MULTIPLE_ADDRESSES_FOUND_ID,
                'name'  => 'Multiple Addresses Found',
                'isError' => true
            ],
            [
                'id'    => OrderStatusUtility::INVALID_POSTAL_CODE_ID,
                'name'  => 'Invalid Postal Code',
                'isError' => true
            ],
            [
                'id'    => OrderStatusUtility::INVALID_COUNTRY_ID,
                'name'  => 'Invalid Country',
                'isError' => true
            ],
            [
                'id'    => OrderStatusUtility::INVALID_STREET_ID,
                'name'  => 'Invalid Street Address',
                'isError' => true
            ],
            [
                'id'    => OrderStatusUtility::INVALID_PHONE_NUMBER,
                'name'  => 'Invalid phone number',
                'isError' => true
            ],



            /**
             * Order Approval Error Statuses
             */
            [
                'id'    => OrderStatusUtility::UNMAPPED_SKU,
                'name'  => 'Unmapped Sku',
                'isError' => true
            ],



            /**
             * Fulfillment Operations
             */
            [
                'id'    => OrderStatusUtility::PENDING_FULFILLMENT_ID,
                'name'  => 'Pending Fulfillment',
                'isError' => false
            ],
            [
                'id'    => OrderStatusUtility::PULLED_ID,
                'name'  => 'Pulled',
                'isError' => false
            ],
            [
                'id'    => OrderStatusUtility::PICKED_ID,
                'name'  => 'Picked',
                'isError' => false
            ],




            /**
             * Shipping Operations
             */
            [
                'id'    => OrderStatusUtility::PARTIALLY_SHIPPED_ID,
                'name'  => 'Partially Shipped',
                'isError' => false
            ],
            [
                'id'    => OrderStatusUtility::FULLY_SHIPPED_ID,
                'name'  => 'Fully Shipped',
                'isError' => false
            ],

        ];
    }
}
