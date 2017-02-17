<?php

use Illuminate\Database\Seeder;
use App\Utilities\ShipmentStatusUtility;

class ShipmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ShipmentStatus')->insert(
            $this->getStatuses()
        );
    }


    private function getStatuses ()
    {
        return [

            /**
             * LifeCycle
             */
            [
                'id'    => ShipmentStatusUtility::PENDING_INVENTORY_RESERVATION,
                'name'  => 'Pending inventory reservation',
                'isError' => false
            ],
            [
                'id'    => ShipmentStatusUtility::PENDING,
                'name'  => 'Pending',
                'isError' => false
            ],
            [
                'id'    => ShipmentStatusUtility::PARTIALLY_SHIPPED,
                'name'  => 'Partially Shipped',
                'isError' => false
            ],
            [
                'id'    => ShipmentStatusUtility::FULLY_SHIPPED,
                'name'  => 'Fully Shipped',
                'isError' => false
            ],


            /**
             * Address Error Statuses
             */
            [
                'id'    => ShipmentStatusUtility::INVALID_CITY_ID,
                'name'  => 'Invalid City',
                'isError' => true
            ],
            [
                'id'    => ShipmentStatusUtility::INVALID_STATE_ID,
                'name'  => 'Invalid State',
                'isError' => true
            ],
            [
                'id'    => ShipmentStatusUtility::INVALID_ADDRESS_ID,
                'name'  => 'Invalid Address',
                'isError' => true
            ],
            [
                'id'    => ShipmentStatusUtility::MULTIPLE_ADDRESSES_FOUND_ID,
                'name'  => 'Multiple Addresses Found',
                'isError' => true
            ],
            [
                'id'    => ShipmentStatusUtility::INVALID_POSTAL_CODE_ID,
                'name'  => 'Invalid Postal Code',
                'isError' => true
            ],
            [
                'id'    => ShipmentStatusUtility::INVALID_COUNTRY_ID,
                'name'  => 'Invalid Country',
                'isError' => true
            ],
            [
                'id'    => ShipmentStatusUtility::INVALID_STREET_ID,
                'name'  => 'Invalid Street Address',
                'isError' => true
            ],
            [
                'id'    => ShipmentStatusUtility::INVALID_PHONE_NUMBER,
                'name'  => 'Invalid phone number',
                'isError' => true
            ],

            
            /**
             * Inventory Error Statuses
             */
            [
                'id'    => ShipmentStatusUtility::INSUFFICIENT_INVENTORY,
                'name'  => 'Insufficient inventory',
                'isError' => true
            ],

        ];
    }
}
