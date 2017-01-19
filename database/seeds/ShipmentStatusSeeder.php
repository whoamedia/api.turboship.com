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
                'name'  => 'Fully Complete',
                'isError' => false
            ],

        ];
    }
}
