<?php

use Illuminate\Database\Seeder;
use App\Utilities\ShippingStationTypeUtility;

class ShippingStationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ShippingStationType')->insert(
            $this->getShippingStationTypes()
        );
    }


    private function getShippingStationTypes ()
    {
        return [

            /**
             * LifeCycle
             */
            [
                'id'    => ShippingStationTypeUtility::STANDARD,
                'name'  => 'Standard',
            ],
            [
                'id'    => ShippingStationTypeUtility::AUTO_BAGGER,
                'name'  => 'Auto Bagger',
            ],

        ];
    }
}
