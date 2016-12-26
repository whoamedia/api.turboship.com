<?php

use Illuminate\Database\Seeder;

class ShipmentAlgorithmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ShipmentAlgorithm')->insert(
            $this->getAlgorithms()
        );
    }


    private function getAlgorithms ()
    {
        return [
            [
                'id'    => \App\Utilities\ShipmentAlgorithmUtility::ONE_SHIPMENT_PER_ORDER,
                'name'  => 'One Shipment Per Order'
            ]
        ];
    }
}
