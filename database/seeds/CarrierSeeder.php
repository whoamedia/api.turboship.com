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
                'id'        => CarrierUtility::USPS,
                'name'      => 'United States Postal Service',
                'symbol'    => 'USPS',
            ],
            [
                'id'        => CarrierUtility::UPS,
                'name'      => 'United Postal Service',
                'symbol'    => 'UPS',
            ],
            [
                'id'        => CarrierUtility::UPS_MAIL_INNOVATIONS,
                'name'      => 'UPS Mail Innovations',
                'symbol'    => 'UPS_MAIL_INNOVATIONS',
            ],
            [
                'id'        => CarrierUtility::DHL_GLOBAL_MAIL,
                'name'      => 'DHL Global Mail',
                'symbol'    => 'DHL_GLOBAL_MAIL',
            ],
        ];
    }
}
