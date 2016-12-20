<?php

use Illuminate\Database\Seeder;
use App\Utilities\CarrierUtility;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Carrier')->insert(
            $this->getCarriers()
        );
    }


    private function getCarriers ()
    {
        return [
            [
                'id'        => CarrierUtility::USPS_ID,
                'name'      => 'United States Postal Service',
                'symbol'    => 'USPS',
            ],
            [
                'id'        => CarrierUtility::UPS_ID,
                'name'      => 'United Postal Service',
                'symbol'    => 'UPS',
            ],
            [
                'id'        => CarrierUtility::DHL_ID,
                'name'      => 'DHL',
                'symbol'    => 'DHL',
            ],
        ];
    }
}
